<?php
/**
 * This file contains methods that imitate
 * action on arrays, just like on a database.
 *
 * @package Simpler
 * @version 2.0
 */

namespace Simpler\Components;

use Simpler\Components\Interfaces\ArrayDBInterface;

class ArrayDB implements ArrayDBInterface
{
    private static ?string $_column = null;
    private static ?string $_value = null;
    private static array $_conditions = [];
    private static ?string $_orderColumn = null;
    private array $_targetArray = [];
    private static bool $_where = false;
    private static string $_ordering = 'asc';

    //------------------------------------------------------------------
    // RESET METHOD
    //------------------------------------------------------------------
    private function _reset(): void
    {
        self::$_column = null;
        self::$_value = null;
        self::$_conditions = [];

        self::$_orderColumn = null;
        $this->_targetArray = [];

        self::$_where = false;
    }


    //------------------------------------------------------------------
    // OPERATORS WHERE METHODS
    //------------------------------------------------------------------
    private function _getOperator(?string $value)
    {
        if (strpos($value, '!=') !== false) {
            return '!=';
        }

        if (strpos($value, '>') !== false) {
            return strpos($value, '>=') !== false ? '>=' : '>';
        }

        if (strpos($value, '<') !== false) {
            return strpos($value, '<=') !== false ? '<=' : '<';
        }

        return false;
    }

    private function _whichOperator(?string $value): bool
    {
        $operator = $this->_getOperator($value);

        return strpos($value, $operator) !== false;
    }

    private function _operatorsWhere(array $source_array, string $column, ?string $value): array
    {
        $operator = $this->_getOperator($value);

        if ($operator !== false) {
            $value = $this->_resetValue($value);

            foreach ($source_array as $key => $cols) {
                if (
                    ($cols[$column] == $value && $operator == '!=') ||
                    ($cols[$column] > $value && $operator == '<=') ||
                    ($cols[$column] < $value && $operator == '>=') ||
                    ($cols[$column] >= $value && $operator == '<') ||
                    ($cols[$column] <= $value && $operator == '>')
                ) {
                    unset($source_array[$key]);
                }
            }

            $source_array = array_values($source_array);
        }

        // Target array
        return $source_array;
    }

    //------------------------------------------------------------------
    // WHERE BUILD METHODS
    //------------------------------------------------------------------
    public static function where($columns = null, ?string $value = null): ArrayDB
    {
        if (is_string($columns)) {
            self::$_column = $columns;
            self::$_value = $value;

            self::$_where = true;
        } elseif (is_array($columns)) {
            self::$_conditions = $columns;
            self::$_where = true;
        }

        return new self();
    }

    private function _resetValue(?string $value): string
    {
        $value = str_replace(['!=', '<>'], '', $value);

        return trim($value);
    }

    private function _traitBuildWhere(array $source_array, string $column, ?string $value): array
    {
        $value = $this->_resetValue($value);
        $keys = array_keys(array_column($source_array, $column), $value);

        // Target array
        return array_map(static function ($k) use ($source_array) {
            return $source_array[$k];
        }, $keys);
    }

    private function _buildWhere(array $source_array): array
    {
        // When in where is != or <>.
        if (is_null(self::$_column)) {
            foreach (self::$_conditions as $column => $value) {
                if ($this->_whichOperator($value)) {
                    $source_array = $this->_operatorsWhere($source_array, $column, $value);
                } else {
                    $source_array = $this->_traitBuildWhere($source_array, $column, $value);
                }
            }
        } elseif ($this->_whichOperator(self::$_value)) {
            $source_array = $this->_operatorsWhere($source_array, self::$_column, self::$_value);
        } else {
            $source_array = $this->_traitBuildWhere($source_array, self::$_column, self::$_value);
        }

        // Reset vars
        $this->_reset();

        // Target array
        return $source_array;
    }

    //------------------------------------------------------------------
    // ORDER BY METHODS
    //------------------------------------------------------------------
    public static function orderBy(string $column, ?string $ordering = null): ArrayDB
    {
        self::$_orderColumn = $column;
        self::$_ordering = $ordering;

        return new self();
    }

    private function _buildOrderBy(array $a, array $b): bool
    {
        if (self::$_ordering === 'asc') {
            return $a[self::$_orderColumn] > $b[self::$_orderColumn];
        }

        return $a[self::$_orderColumn] < $b[self::$_orderColumn];
    }

    //------------------------------------------------------------------
    // GET METHODS
    //------------------------------------------------------------------
    public function get(array $source_array, ?int $limit, string $source_columns): array
    {
        // Array
        if (self::$_where) {
            $source_array = $this->_buildWhere($source_array);
        }

        $target_array = [];

        // Columns
        $target_columns = [];

        // Ordering
        if (!is_null(self::$_orderColumn)) {
            usort($source_array, [$this, '_buildOrderBy']);
        }

        // Searched records in the array.
        if (strpos($source_columns, ',') !== false || count($source_array) > 1) {
            $temp_columns = explode(',', $source_columns);
            foreach ($temp_columns as $column) {
                $target_columns[] = array_column($source_array, trim($column));
            }

            foreach ($target_columns as $key => $columns) {
                foreach ($columns as $_key => $value) {
                    $target_array[$_key][trim($temp_columns[$key])] = $value;
                }
            }
        } else {
            $columns = array_column($source_array, $source_columns)[0] ?? null;
            if (!is_null($columns)) {
                $target_array[$source_columns] = array_column($source_array, $source_columns)[0] ?? $columns;
            }
        }

        // Limit
        if (!is_null($limit) && $limit > 0 && isset($target_array)) {
            $limit = min(count($source_array), $limit);
            $target_array = array_slice($target_array, 0, $limit);
        }

        // Reset vars
        $this->_reset();

        // Target array
        return $target_array;
    }

    public function getOne(array $source_array, string $source_columns)
    {
        // Array
        if (self::$_where) {
            $source_array = $this->_buildWhere($source_array);
        }

        $target_array = [];

        // Columns
        $target_columns = [];

        // Ordering
        if (!is_null(self::$_orderColumn)) {
            usort($source_array, [$this, '_buildOrderBy']);
        }

        // Searched records in the array.
        if (strpos($source_columns, ',') !== false) {
            $temp_columns = explode(',', $source_columns);
            foreach ($temp_columns as $column) {
                $target_columns[] = array_column($source_array, trim($column));
            }

            foreach ($target_columns as $key => $columns) {
                foreach ($columns as $_key => $value) {
                    $target_array[$_key][trim($temp_columns[$key])] = $value;
                }
            }

            $target_array = $target_array[0] ?? null;
        } else {
            $columns = array_column($source_array, $source_columns)[0] ?? null;
            if (!is_null($columns)) {
                $target_array[$source_columns] = array_column($source_array, $source_columns)[0] ?? $columns;
            }
        }

        // Reset vars
        $this->_reset();

        // Target array
        return $target_array ?? false;
    }

    public function getValue(array $source_array, string $column)
    {
        if (self::$_where) {
            $source_array = $this->_buildWhere($source_array);
        }

        // Ordering
        if (!is_null(self::$_orderColumn)) {
            usort($source_array, [$this, '_buildOrderBy']);
        }

        // Reset vars
        $this->_reset();

        // Target array
        $target_array = array_column($source_array, trim($column));

        return $target_array[0] ?? false;
    }
}
