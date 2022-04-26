<?php
/**
 * This class migrations SQL helper.
 *
 * @package Simpler
 * @subpackage Database
 * @version 2.0
 */

namespace Simpler\Components\Database\Migrations;

use Simpler\Components\Config;
use Simpler\Components\Database\DB;
use Simpler\Components\Database\Migrations\Interfaces\MigrationsInterface;
use Simpler\Components\Database\Models\Migration as ModelMigration;
use Simpler\Components\Enums\State;
use Simpler\Components\Facades\File;
use Simpler\Components\Stubs;
use Exception;
use RuntimeException;

class Migrations extends DB implements MigrationsInterface
{
    /**
     * Create migration.
     *
     * @param string|null $migrationName
     * @return string
     */
    public static function create(?string $migrationName): string
    {
        try {
            $config = Config::get('db.migrations');

            if (strlen($migrationName) > $config['maxCharsName']) {
                throw new RuntimeException(
                    __('framework.migrations.nameTooLong', [
                        'migration' => $migrationName,
                    ])
                );
            }

            if (!DB::tableExists('migrations')) {
                $tableName = self::getPrefix().'migrations';

                DB::rawQuery(
                    "
                    CREATE TABLE `$tableName` (
                      `id` int UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
                      `migration` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
                      `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
                      `migrate` smallint UNSIGNED NOT NULL DEFAULT 0,
                      `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                      `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;"
                );
            }

            if (ModelMigration::query()->where('name', $migrationName)->exists()) {
                self::disconnect();

                throw new RuntimeException(
                    __('framework.migrations.alreadyExists', [
                        'migration' => $migrationName,
                    ])
                );
            }

            $migration = 'Migration_'.date('Y_m_d').'_'.time().'_'.$migrationName;
            $migrationFile = $config['path'].DS.$migration.'.php';

            // Create migration file base on stub migration.
            if (!File::has($migrationFile)) {
                Stubs::init('migration')
                    ->setClassName($migration)
                    ->save($migrationFile);

                // Add migration to database.
                $status = ModelMigration::query()->insert([
                    'migration' => $migration,
                    'name' => $migrationName,
                ]);

                self::disconnect();

                if (!$status) {
                    throw new RuntimeException(
                        __('framework.migrations.notBeCreated', [
                            'migration' => $migrationName,
                        ])
                    );
                }
            }

            return $migration;
        } catch (Exception $e) {
            throw new RuntimeException($e->getMessage());
        }
    }

    /**
     * Up migration to database.
     *
     * @param mixed $migration
     * @return void
     */
    public static function up($migration): void
    {
        try {
            self::migrate($migration, 'up');
        } catch (Exception $e) {
            throw new RuntimeException($e->getMessage());
        }
    }

    /**
     * @param mixed $migration
     * @return void
     */
    public static function down($migration): void
    {
        try {
            self::migrate($migration, 'down');
        } catch (Exception $e) {
            throw new RuntimeException($e->getMessage());
        }
    }

    /**
     * Rollback migration from database.
     *
     * @param bool $migrate
     * @param ?string $migration
     * @return array
     */
    public static function get(bool $migrate, ?string $migration = null): array
    {
        try {
            $migrations = ModelMigration::query()->where('migrate', $migrate);

            if (!is_null($migration)) {
                $migrations->where('migration', $migration);
            }

            if ($migrate) {
                $migrations->orderBy('id', 'DESC');
            }

            return $migrations->get('id, name, migration');
        } catch (Exception $e) {
            throw new RuntimeException($e->getMessage());
        }
    }

    /**
     * Simply trait to down and up migration.
     *
     * @param mixed $migration
     * @param string $method
     * @return void
     */
    private static function migrate($migration, string $method): void
    {
        if (empty($migration->migration ?? '')) {
            throw new RuntimeException(
                __('framework.migrations.classIsEmpty', [
                    'migration' => $migration->name,
                ])
            );
        }

        container('Database\Migrations\\'.$migration->migration)->call($method);

        try {
            ModelMigration::query()
                ->where('name', $migration->name)
                ->update(['migrate' => compare($method, 'up') ? State::YES : State::NO]);
        } catch (Exception $e) {
            throw new RuntimeException($e->getMessage());
        }
    }
}
