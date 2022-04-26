<?php

namespace Simpler\Components\Database\Interfaces;

use Simpler\Components\Database\Model;
use Simpler\Components\Database\Paginator;

interface ModelInterface
{
    /**
     * @return Model
     */
    public static function query(): Model;

    /**
     * @param int|null $limit
     * @return Model
     */
    public function limit(?int $limit = null): Model;

    /**
     * @param int|null $offset
     * @return Model
     */
    public function offset(?int $offset = null): Model;

    /**
     * @param bool $asObject
     * @return Model
     */
    public function asObject(bool $asObject = true): Model;

    /**
     * @param string $columns
     * @return mixed
     */
    public function get(string $columns = ''): array;

    /**
     * @param string $columns
     * @return mixed
     */
    public function first(string $columns = '*');

    /**
     * @param string $column
     * @return mixed
     */
    public function value(string $column);

    /**
     * @return int
     */
    public function count(): int;

    /**
     * @param array $insertData
     * @return mixed
     */
    public function insert(array $insertData);

    /**
     * @param array $tableData
     * @return bool
     */
    public function update(array $tableData): bool;

    /**
     * @return bool
     */
    public function delete(): bool;

    /**
     * @param string $column
     * @param mixed $values
     * @param string $operator
     * @return Model
     */
    public function where(string $column, $values, string $operator = ''): Model;

    /**
     * @param string $column
     * @param mixed $values
     * @param string $operator
     * @return Model
     */
    public function orWhere(string $column, $values, string $operator = ''): Model;

    /**
     * @param string $column
     * @return Model
     */
    public function whereNull(string $column): Model;

    /**
     * @param string $column
     * @return Model
     */
    public function whereNotNull(string $column): Model;

    /**
     * @param string $column
     * @param array $values
     * @return Model
     */
    public function whereIn(string $column, array $values): Model;

    /**
     * @param string $column
     * @param array $values
     * @return Model
     */
    public function whereNotIn(string $column, array $values): Model;

    /**
     * @return bool
     */
    public function exists(): bool;

    /**
     * @param string $column
     * @param string $operator
     * @param mixed $values
     * @return Model
     */
    public function having(string $column, string $operator, $values): Model;

    /**
     * @param string $column
     * @param string $direction
     * @return Model
     */
    public function orderBy(string $column, string $direction = 'ASC'): Model;

    /**
     * @param string $column
     * @return Model
     */
    public function groupBy(string $column): Model;

    /**
     * @param string $table
     * @param string $statement
     * @param string $type
     * @param string|null $customTable
     * @return Model
     */
    public function join(string $table, string $statement, string $type = 'left', ?string $customTable = null): Model;

    /**
     * @param int $pageLimit
     * @return Paginator
     */
    public function paginator(int $pageLimit = 20): Paginator;

    /**
     * @param mixed $relations
     * @return Model
     */
    public function with($relations = null): Model;

    /**
     * @param bool $alias
     * @return string
     */
    public function getTableName(bool $alias = true): string;

    /**
     * @return string
     */
    public function getTableAlias(): string;

    /**
     * @return array|null
     */
    public static function getRawClauses(): ?array;
}
