<?php

namespace Simpler\Components\Database\Interfaces;

use Envms\FluentPDO\Queries\Delete;
use Envms\FluentPDO\Queries\Insert;
use Envms\FluentPDO\Queries\Select;
use Envms\FluentPDO\Queries\Update;
use Envms\FluentPDO\Query;
use PDO;

interface DBInterface
{
    /**
     * @param string $connectionName
     * @return void
     */
    public static function beginTransaction(string $connectionName = 'default'): void;

    /**
     * @return void
     */
    public static function commit(): void;

    /**
     * @return void
     */
    public static function rollback(): void;

    /**
     * @param bool $stop
     * @return void
     */
    public static function disconnect(bool $stop = false): void;

    /**
     * @param string $query
     * @param array $bind
     * @param int $mode
     * @return mixed
     */
    public static function rawQueryAll(string $query, array $bind = [], int $mode = PDO::FETCH_CLASS);

    /**
     * @param string $query
     * @return void
     */
    public static function rawQuery(string $query): void;

    /**
     * @param string $query
     * @param array $bind
     * @param int $mode
     * @return mixed
     */
    public static function rawQueryOne(string $query, array $bind = [], int $mode = PDO::FETCH_OBJ);

    /**
     * @return PDO
     */
    public static function pdo(): PDO;

    /**
     * @return string
     */
    public static function getConnectionName(): string;

    /**
     * @param string $prefix
     * @return void
     */
    public static function setPrefix(string $prefix): void;

    /**
     * @return string
     */
    public static function getPrefix(): string;

    /**
     * @param string|null $connectionName
     * @return void
     */
    public static function addConnection(?string $connectionName = null): void;

    /**
     * @param string $table
     * @return bool
     */
    public static function tableExists(string $table): bool;

    /**
     * @return Query|Select|Insert|Update|Delete
     */
    public static function connect();
}
