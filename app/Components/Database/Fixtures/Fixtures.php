<?php
/**
 * This class migrations SQL helper.
 *
 * @package Simpler
 * @subpackage Database
 * @version 2.0
 */

namespace Simpler\Components\Database\Fixtures;

use Simpler\Components\Config;
use Simpler\Components\Database\DB;
use Simpler\Components\Database\Fixtures\Interfaces\FixturesInterface;
use Simpler\Components\Database\Models\Fixture as ModelFixture;
use Simpler\Components\Enums\State;
use Simpler\Components\Facades\File;
use Simpler\Components\Stubs;
use Exception;
use RuntimeException;

class Fixtures extends DB implements FixturesInterface
{
    /**
     * Create migration.
     *
     * @param string|null $fixtureName
     * @return string
     */
    public static function create(?string $fixtureName): string
    {
        try {
            $config = Config::get('db.fixtures');

            if (strlen($fixtureName) > $config['maxCharsName']) {
                throw new RuntimeException(
                    __('framework.fixtures.nameTooLong', [
                        'fixture' => $fixtureName,
                    ])
                );
            }

            if (!DB::tableExists('fixtures')) {
                $tableName = self::getPrefix().'fixtures';

                DB::rawQuery(
                    "
                    CREATE TABLE `$tableName` (
                      `id` int UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
                      `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
                      `migrate` smallint UNSIGNED NOT NULL DEFAULT 0,
                      `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                      `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;"
                );
            }

            if (ModelFixture::query()->where('name', $fixtureName)->exists()) {
                self::disconnect();

                throw new RuntimeException(
                    __('framework.fixtures.alreadyExists', [
                        'fixture' => $fixtureName,
                    ])
                );
            }

            $fixtureFile = $config['path'].DS.$fixtureName.'.php';

            // Create migration file base on stub fixture.
            if (!File::has($fixtureFile)) {
                Stubs::init('fixture')
                    ->setClassName($fixtureName)
                    ->save($fixtureFile);

                // Add fixture to database.
                $status = ModelFixture::query()->insert([
                    'name' => $fixtureName,
                ]);

                self::disconnect();

                if (!$status) {
                    throw new RuntimeException(
                        __('framework.fixtures.notBeCreated', [
                            'fixture' => $fixtureName,
                        ])
                    );
                }
            }

            return $fixtureName;
        } catch (Exception $e) {
            throw new RuntimeException($e->getMessage());
        }
    }

    /**
     * Up migration to database.
     *
     * @param null|string $fixtureName
     * @return void
     */
    public static function run(?string $fixtureName): void
    {
        try {
            if (empty($fixtureName ?? '')) {
                throw new RuntimeException(
                    __('framework.fixtures.classIsEmpty', [
                        'name' => $fixtureName,
                    ])
                );
            }

            container('Database\Fixtures\\'.$fixtureName)->call('run');

            try {
                ModelFixture::query()
                    ->where('name', $fixtureName)
                    ->update(['migrate' => State::YES]);
            } catch (Exception $e) {
                throw new RuntimeException($e->getMessage());
            }
        } catch (Exception $e) {
            throw new RuntimeException($e->getMessage());
        }
    }

    /**
     * Get all fixtures from database.
     *
     * @param null|string $fixture
     * @return array
     */
    public static function get(?string $fixture = null): array
    {
        try {
            $migrations = ModelFixture::query()->where('migrate', State::NO);

            if (!is_null($fixture)) {
                $migrations->where('name', $fixture);
            }

            return $migrations->get('id, name');
        } catch (Exception $e) {
            throw new RuntimeException($e->getMessage());
        }
    }
}
