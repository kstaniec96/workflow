<?php
/**
 * This file is used to initialize the connection
 * to the database and operations on it using MysqliDb.
 *
 * @package Simpler
 * @subpackage Database
 * @version 2.0
 *
 * @see https://github.com/envms/fluentpdo
 */

namespace Simpler\Components\Database;

use Simpler\Components\Config;
use Simpler\Components\Database\Interfaces\DBInterface;
use Envms\FluentPDO\Queries\Delete;
use Envms\FluentPDO\Queries\Insert;
use Envms\FluentPDO\Queries\Select;
use Envms\FluentPDO\Queries\Update;
use Envms\FluentPDO\Query;
use PDO;
use PDOException;
use RuntimeException;

class DB implements DBInterface
{
    /** @var string|null */
    protected static ?string $connectionName = 'default';

    /** @var bool */
    private static bool $beginTransaction = false;

    /** @var string */
    private static string $prefix = '';

    /** @var Query|Select|Insert|Update|Delete */
    private static object $db;

    /**
     * Init default database connection and configurations.
     *
     * @param string $connectionName
     * @return void
     */
    public static function init(string $connectionName = 'default'): void
    {
        try {
            self::$connectionName = $connectionName;

            // Configuration data
            $config = self::getConfig($connectionName);

            // Set database connect handler
            $pdo = new PDO($config['dsn'], $config['username'], $config['password'], $config['options']);
            self::$db = new Query($pdo);
        } catch (PDOException $e) {
            throw new RuntimeException($e->getMessage());
        }
    }

    /**
     * Start transaction for connect.
     *
     * @param string $connectionName
     * @return void
     */
    public static function beginTransaction(string $connectionName = 'default'): void
    {
        try {
            self::init($connectionName);

            self::$beginTransaction = true;
            self::$db->getPdo()->beginTransaction();
        } catch (PDOException $e) {
            throw new RuntimeException($e->getMessage());
        }
    }

    /**
     * Commit transaction.
     *
     * @return void
     */
    public static function commit(): void
    {
        try {
            self::$beginTransaction = false;

            self::pdo()->commit();
            self::$db->close();
        } catch (PDOException $e) {
            throw new RuntimeException($e->getMessage());
        }
    }

    /**
     * Rollback transaction.
     *
     * @return void
     */
    public static function rollback(): void
    {
        try {
            self::$beginTransaction = false;

            self::pdo()->rollBack();
            self::$db->close();
        } catch (PDOException $e) {
            throw new RuntimeException($e->getMessage());
        }
    }

    /**
     * Rollback transaction.
     *
     * @param bool $stop
     * @return void
     */
    public static function disconnect(bool $stop = false): void
    {
        try {
            if (!self::$beginTransaction && !$stop) {
                self::$db->close();
            }
        } catch (PDOException $e) {
            throw new RuntimeException($e->getMessage());
        }
    }

    /**
     * Raw query all SQL.
     *
     * @param string $query
     * @param array $bind
     * @param string $mode
     * @return mixed
     */
    public static function rawQueryAll(string $query, array $bind = [], string $mode = PDO::FETCH_CLASS)
    {
        try {
            $stmt = self::pdo()->prepare($query);
            $stmt->execute($bind);

            return $stmt->fetchColumn($mode);
        } catch (PDOException $e) {
            throw new RuntimeException($e->getMessage());
        }
    }

    /**
     * Raw query one SQL.
     *
     * @param string $query
     * @param array $bind
     * @param string $mode
     * @return mixed
     */
    public static function rawQueryOne(string $query, array $bind = [], string $mode = PDO::FETCH_OBJ)
    {
        try {
            $stmt = self::pdo()->prepare($query);
            $stmt->execute($bind);

            return $stmt->fetch($mode);
        } catch (PDOException $e) {
            throw new RuntimeException($e->getMessage());
        }
    }

    /**
     * Raw query SQL.
     *
     * @param string $query
     * @return void
     */
    public static function rawQuery(string $query): void
    {
        try {
            self::pdo()->prepare($query)->execute();
        } catch (PDOException $e) {
            throw new RuntimeException($e->getMessage());
        }
    }

    /**
     * Get PDO instance.
     *
     * @return PDO
     */
    public static function pdo(): PDO
    {
        try {
            return self::$db->getPdo();
        } catch (PDOException $e) {
            throw new RuntimeException($e->getMessage());
        }
    }

    /**
     * Get connection name.
     *
     * @return string
     */
    public static function getConnectionName(): string
    {
        return self::$connectionName;
    }

    /**
     * Set prefix table.
     *
     * @param string $prefix
     * @return void
     */
    public static function setPrefix(string $prefix): void
    {
        self::$prefix = $prefix;
    }

    /**
     * Get prefix table.
     *
     * @return string
     */
    public static function getPrefix(): string
    {
        return self::$prefix;
    }

    /**
     * Set database connection.
     *
     * @param string|null $connectionName
     * @return void
     */
    public static function addConnection(?string $connectionName = null): void
    {
        try {
            $connectionName = $connectionName ?? self::$connectionName;

            if (!compare($connectionName, self::$connectionName)) {
                self::init($connectionName);
            }
        } catch (PDOException $e) {
            throw new RuntimeException($e->getMessage());
        }
    }

    /**
     * Check table exists.
     *
     * @param string $table
     * @return bool
     */
    public static function tableExists(string $table): bool
    {
        try {
            $table = self::getPrefix().$table;

            return self::rawQueryOne("SHOW TABLES LIKE '$table'") !== false;
        } catch (PDOException $e) {
            throw new RuntimeException($e->getMessage());
        }
    }

    /**
     * Init connect Select
     *
     * @return Query|Select|Insert|Update|Delete
     */
    public static function connect()
    {
        try {
            return self::$db;
        } catch (PDOException $e) {
            throw new RuntimeException($e->getMessage());
        }
    }

    /**
     * Get database configurations.
     *
     * @param string $connectionName
     * @return array
     */
    private static function getConfig(string $connectionName = 'default'): array
    {
        // Configuration data
        $config = Config::get('db.connections')[$connectionName];
        self::setPrefix($config['prefix']);

        return [
            'dsn' => $config['driver'].':'.
                'host='.$config['host'].';'.
                'dbname='.$config['dbname'].';'.
                'port='.$config['port'].';'.
                'charset='.$config['charset'],
            'username' => $config['user'],
            'password' => $config['pass'],
            'options' => $config['options'],
        ];
    }
}
