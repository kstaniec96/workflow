<?php
/**
 * This class migration SQL helper.
 *
 * @package Simpler
 * @subpackage Database
 * @version 2.0
 */

namespace Simpler\Components\Database\Migrations;

use Simpler\Components\Database\DB;
use Simpler\Components\Database\Migrations\Interfaces\MigrationInterface;
use Exception;
use RuntimeException;

abstract class Migration extends DB implements MigrationInterface
{
    /** @var string|null */
    protected $connectName;

    /**
     * @return void
     */
    abstract public function up(): void;

    /**
     * @return void
     */
    abstract public function down(): void;

    /**
     * Run Raw SQL.
     *
     * @param string $sql
     * @return void
     */
    public function run(string $sql): void
    {
        try {
            self::addConnection($this->connectName);
            self::rawQuery($sql);
        } catch (Exception $e) {
            throw new RuntimeException($e->getMessage());
        }
    }
}
