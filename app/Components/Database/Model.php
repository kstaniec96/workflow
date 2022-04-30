<?php
/**
 * This class model helper
 * Extension for SQL queries.
 *
 * @package Simpler
 * @subpackage Database
 * @version 2.0
 *
 * @link https://github.com/envms/fluentpdo
 */

namespace Simpler\Components\Database;

use RuntimeException;
use Simpler\Components\Database\Interfaces\ModelInterface;
use Exception;
use Simpler\Components\Exceptions\ServerErrorException;
use Simpler\Components\Exceptions\ThrowException;

abstract class Model extends DB implements ModelInterface
{
    /** @var string|null */
    protected $table;

    /** @var string */
    protected $connectName = 'default';

    /** @var array|null */
    protected $relations;

    /** @var string|null */
    protected $alias;

    /** @var string|null */
    private ?string $tableName = null;

    /** @var string|null */
    private ?string $tableAlias = null;

    /** @var int|null */
    private ?int $limit = null;

    /** @var int|null */
    private ?int $offset = null;

    /** @var array */
    private static array $whereBuilder = [];

    /** @var array */
    private static array $havingBuilder = [];

    /** @var array */
    private static array $orderBuilder = [];

    /** @var array */
    private static array $groupBuilder = [];

    /** @var array */
    private static array $joinBuilder = [];

    /** @var bool */
    private bool $asObject = true;

    /** @var array|null */
    private static ?array $rawClauses = null;

    /**
     * Init query MysqliDb
     *
     * @param string|null $connectName
     * @return Model
     */
    public static function query(?string $connectName = null): Model
    {
        try {
            return (new static())->newQuery($connectName);
        } catch (Exception $e) {
            throw new ThrowException($e);
        }
    }

    /**
     * Set table alias.
     *
     * @param string|null $alias
     * @return $this
     */
    public function setTableAlias(?string $alias): Model
    {
        if (!empty($alias)) {
            $this->tableAlias = $alias;
        }

        return $this;
    }

    /**
     * Set limit records for query.
     *
     * @param int|null $limit
     * @return $this
     */
    public function limit(?int $limit = null): Model
    {
        $this->limit = $limit;

        return $this;
    }

    /**
     * Get query results as object.
     *
     * @param bool $asObject
     * @return $this
     */
    public function asObject(bool $asObject = true): Model
    {
        $this->asObject = $asObject;

        return $this;
    }

    /**
     * Set offset records for query.
     *
     * @param int|null $offset
     * @return $this
     */
    public function offset(?int $offset = null): Model
    {
        $this->offset = $offset;

        return $this;
    }

    /**
     * A convenient SELECT * function.
     *
     * @param string $columns
     * @return array
     */
    public function get(string $columns = ''): array
    {
        try {
            $obj = self::connect()
                ->from($this->getTableName())
                ->limit($this->limit)
                ->offset($this->offset)
                ->asObject($this->asObject);

            $this->whereBuilder($obj);
            $this->joinBuilder($obj);
            $this->havingBuilder($obj);
            $this->orderByBuilder($obj);

            if (!empty($columns)) {
                $obj->select($columns, true);
            }

            $this->groupByBuilder($obj);

            self::$rawClauses = $obj->getRawClauses();
            $results = [];

            foreach ($obj as $row) {
                $results[] = $row;
            }

            return $results;
        } catch (Exception $e) {
            throw new RuntimeException($e->getMessage());
        }
    }

    /**
     * A convenient SELECT * function to get one record.
     *
     * @param string $columns
     * @return mixed
     */
    public function first(string $columns = '')
    {
        try {
            return current($this->limit(1)->get($columns));
        } catch (Exception $e) {
            throw new RuntimeException($e->getMessage());
        }
    }

    /**
     * A convenient SELECT COLUMN function to get a single
     * column value from one row.
     *
     * @param string $column
     * @return array|mixed|null
     */
    public function value(string $column)
    {
        try {
            $this->asObject = false;

            return $this->first()[$column] ?? null;
        } catch (Exception $e) {
            throw new RuntimeException($e->getMessage());
        }
    }

    /**
     * Count rows from SQL query.
     *
     * @return int
     */
    public function count(): int
    {
        try {
            $this->asObject = false;

            return $this->first('COUNT(*)')['COUNT(*)'];
        } catch (Exception $e) {
            throw new RuntimeException($e->getMessage());
        }
    }

    /**
     * Insert method to add several rows at once
     *
     * @param array $insertData
     * @return false|int
     */
    public function insert(array $insertData)
    {
        try {
            $query = self::connect()
                ->insertInto($this->getTableName(false))
                ->values($insertData);

            self::$rawClauses = $query->getRawClauses();
            $execute = $query->execute();

            if (!$execute) {
                throw new ServerErrorException('An error occurred while inserting the data');
            }

            return $execute;
        } catch (Exception $e) {
            throw new ThrowException($e);
        }
    }

    /**
     * Update query. Be sure to first call the "where" method.
     *
     * @param array $tableData
     * @return bool
     */
    public function update(array $tableData): bool
    {
        try {
            $query = self::connect()
                ->update($this->getTableName(false))
                ->set($tableData);

            $this->whereBuilder($query);
            self::$rawClauses = $query->getRawClauses();

            if (!$query->execute()) {
                throw new ServerErrorException('An error occurred while updating the data');
            }

            return true;
        } catch (Exception $e) {
            throw new RuntimeException($e->getMessage());
        }
    }

    /**
     * Delete query. Call the "where" method first.
     *
     * @return bool
     */
    public function delete(): bool
    {
        try {
            $query = self::connect()->deleteFrom($this->getTableName(false));

            $this->whereBuilder($query);
            self::$rawClauses = $query->getRawClauses();

            return $query->execute();
        } catch (Exception $e) {
            throw new RuntimeException($e->getMessage());
        }
    }

    /**
     * This method allows you to specify multiple
     * (method chaining optional) AND WHERE statements for SQL queries.
     *
     * @param string $column
     * @param mixed $values
     * @param string $operator
     * @return $this
     */
    public function where(string $column, $values, string $operator = ''): Model
    {
        self::$whereBuilder[] = [$this->joinData($column, $operator), $values, 'AND'];

        return new static();
    }

    /**
     * This method allows you to specify multiple (method chaining optional)
     * OR WHERE statements for SQL queries.
     *
     * @param string $column
     * @param mixed $values
     * @param string $operator
     * @return $this
     */
    public function orWhere(string $column, $values, string $operator = ''): Model
    {
        self::$whereBuilder[] = [$this->joinData($column, $operator), $values, 'OR'];

        return new static();
    }

    /**
     * Where query operator is "IS" NULL.
     *
     * @param string $column
     * @return Model
     */
    public function whereNull(string $column): Model
    {
        self::$whereBuilder[] = [$column, null, 'IS'];

        return new static();
    }

    /**
     * Where query operator is "IS NOT".
     *
     * @param string $column
     * @return Model
     */
    public function whereNotNull(string $column): Model
    {
        self::$whereBuilder[] = [$column, [], 'IS NOT NULL'];

        return new static();
    }

    /**
     * Where query operator is "IN".
     *
     * @param string $column
     * @param array $values
     * @return $this
     */
    public function whereIn(string $column, array $values): Model
    {
        self::$whereBuilder[] = [$column, $values, 'IN'];

        return new static();
    }

    /**
     * Where query is NOT IN operator.
     *
     * @param string $column
     * @param array $values
     * @return $this
     */
    public function whereNotIn(string $column, array $values): Model
    {
        self::$whereBuilder[] = [$column, $values, 'NOT IN'];

        return new static();
    }

    /**
     * Whether the value searched for exists.
     *
     * @return bool
     */
    public function exists(): bool
    {
        try {
            return !empty($this->value('id'));
        } catch (Exception $e) {
            throw new RuntimeException($e->getMessage());
        }
    }

    /**
     * Add HAVING query
     *
     * @param string $column
     * @param string $operator
     * @param mixed $values
     * @return $this
     */
    public function having(string $column, string $operator, $values): Model
    {
        self::$havingBuilder[] = [$this->joinData($column, $operator, $values)];

        return new static();
    }

    /**
     * Add ORDER BY to query
     *
     * @param string $column
     * @param string $direction
     * @return $this
     */
    public function orderBy(string $column, string $direction = 'ASC'): Model
    {
        try {
            self::$orderBuilder[] = [$this->joinData($column, $direction)];
        } catch (Exception $e) {
            throw new RuntimeException($e->getMessage());
        }

        return new static();
    }

    /**
     * Add GROUP BY to query
     *
     * @param string $column
     * @return $this
     */
    public function groupBy(string $column): Model
    {
        self::$groupBuilder[] = [$column];

        return new static();
    }

    /**
     * Tadd JOIN to query
     *
     * @param string $table
     * @param string $statement
     * @param string $type
     * @param string|null $customTable
     * @return $this
     */
    public function join(string $table, string $statement, string $type = 'left', ?string $customTable = null): Model
    {
        try {
            $tableName = $table;
            $tableAlias = explode(' ', $table)[1] ?? 'join';

            self::$joinBuilder[] = [
                $this->joinData(
                    $customTable ?? self::getPrefix().$tableName,
                    'ON',
                    $statement
                ),
                $tableAlias,
                $type,
            ];
        } catch (Exception $e) {
            throw new RuntimeException($e->getMessage());
        }

        return new static();
    }

    /**
     * Pagination wrapper to get()
     *
     * @param int $pageLimit
     * @return Paginator
     */
    public function paginator(int $pageLimit = 20): Paginator
    {
        try {
            return Paginator::init($pageLimit, $this);
        } catch (Exception $e) {
            throw new ThrowException($e);
        }
    }

    /**
     * Add relations to result query.
     *
     * @param mixed $relations
     * @return $this
     */
    public function with($relations = null): Model
    {
        try {
            if (!empty($this->relations) && !empty($relations)) {
                if (!is_array($relations)) {
                    $relations = [$relations];
                }

                foreach ($relations as $relation) {
                    $explodeRelation = explode(' ', $relation);

                    $relationName = $explodeRelation[0];
                    $modelRelation = $this->relations[$relationName] ?? null;

                    if (is_null($modelRelation)) {
                        throw new ServerErrorException('Relation "'.$relationName.'" not found!');
                    }

                    $this->initRelation($modelRelation, $explodeRelation[1] ?? null);
                }


            }
        } catch (Exception $e) {
            throw new ThrowException($e);
        }

        return $this;
    }

    /**
     * Get current table name.
     *
     * @param bool $alias
     * @return string
     */
    public function getTableName(bool $alias = true): string
    {
        if (is_null($this->table)) {
            $explode = explode('\\', static::class);

            $tableName = end($explode);
            $tableName = preg_replace('/(?<!\ )[A-Z]/', '_$0', lcfirst($tableName)).'s';
        } else {
            $tableName = $this->table;
        }

        $tableName = "`".strtolower($tableName)."`";
        $this->tableName = self::getPrefix().$tableName;

        if (!$alias) {
            return $tableName;
        }

        return $tableName.' '.($this->getTableAlias());
    }

    /**
     * Get current table alias.
     *
     * @return string
     */
    public function getTableAlias(): string
    {
        if (!is_null($this->tableAlias)) {
            return $this->tableAlias;
        }

        if (is_null($this->tableName)) {
            $this->getTableName();
        }

        return $this->alias ?? $this->tableName;
    }

    /**
     * Get last query.
     *
     * @return array|null
     */
    public static function getRawClauses(): ?array
    {
        return self::$rawClauses;
    }

    /**
     * Object instance query method
     *
     * @param string|null $connectName
     * @return Model
     */
    private function newQuery(?string $connectName): Model
    {
        try {
            self::addConnection($connectName ?? $this->connectName);
            $this->reset();
        } catch (Exception $e) {
            throw new ThrowException($e);
        }

        return $this;
    }

    /**
     * Init relation with join query.
     *
     * @param array $modelRelation
     * @param null|string $alias
     * @return void
     */
    private function initRelation(array $modelRelation, ?string $alias): void
    {
        try {
            /** @var Model $relationModel */
            $relationModel = (new $modelRelation[0]);

            if (!empty($alias)) {
                $relationModel->setTableAlias($alias);
            }

            $statement =
                $relationModel->getTableAlias().'.'.$modelRelation[2]
                .' = '.
                $this->getTableAlias().'.'.$modelRelation[1];

            $this->join($relationModel->getTableName(), $statement, $modelRelation[3] ?? 'INNER');
        } catch (Exception $e) {
            throw new RuntimeException($e->getMessage());
        }
    }

    /**
     * Join data for SQL.
     *
     * @param string $a
     * @param string $b
     * @param mixed $c
     * @return string
     */
    private function joinData(string $a, string $b, $c = null): string
    {
        return trim($a.' '.$b.' '.($c ?? ''));
    }

    /**
     * Where condition builder.
     *
     * @param object $obj
     * @return void
     */
    private function whereBuilder(object $obj): void
    {
        $whereBuilder = self::$whereBuilder;

        if (!empty($whereBuilder)) {
            foreach ($whereBuilder as $where) {
                $obj->where($where[0], $where[1], $where[2]);
            }
        }
    }

    /**
     * Having condition builder.
     *
     * @param object $obj
     * @return void
     */
    private function havingBuilder(object $obj): void
    {
        $this->queryBuilder($obj, self::$havingBuilder, 'having');
    }

    /**
     * Order by builder.
     *
     * @param object $obj
     * @return void
     */
    private function orderByBuilder(object $obj): void
    {
        $this->queryBuilder($obj, self::$orderBuilder, 'orderBy');
    }

    /**
     * Group by builder.
     *
     * @param object $obj
     * @return void
     */
    private function groupByBuilder(object $obj): void
    {
        $this->queryBuilder($obj, self::$groupBuilder, 'groupBy');
    }

    /**
     * Query builder for: having, orderBy and groupBy.
     *
     * @param object $obj
     * @param string $method
     * @param mixed $builder
     * @return void
     */
    private function queryBuilder(object $obj, $builder, string $method): void
    {
        if (!empty($builder)) {
            foreach ($builder as $stmt) {
                $obj->$method($stmt);
            }
        }
    }

    /**
     * SQL Join builder.
     *
     * @param object $obj
     * @return void
     */
    private function joinBuilder(object $obj): void
    {
        $jsonBuilder = self::$joinBuilder;

        if (!empty($jsonBuilder)) {
            foreach ($jsonBuilder as $join) {
                $statement = $join[0];
                $type = strtolower($join[2] ?? 'LEFT');

                switch ($type) {
                    case 'left':
                        $obj->leftJoin($statement);
                        break;

                    case 'right':
                        $obj->rightJoin($statement);
                        break;

                    case 'inner':
                        $obj->innerJoin($statement);
                        break;

                    case 'outer':
                        $obj->outerJoin($statement);
                        break;

                    case 'full':
                        $obj->fullJoin($statement);
                        break;
                }
            }
        }
    }

    /**
     * Reset builders and config vars.
     *
     * @return void
     */
    private function reset(): void
    {
        self::$whereBuilder = [];
        self::$joinBuilder = [];
        self::$orderBuilder = [];
        self::$groupBuilder = [];
        self::$havingBuilder = [];

        $this->asObject = true;
        $this->limit = null;
        $this->offset = null;

        self::$rawClauses = null;
    }
}
