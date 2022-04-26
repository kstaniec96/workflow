<?php
/**
 * This class migration SQL helper.
 *
 * @package Simpler
 * @subpackage Database
 * @version 2.0
 */

namespace Simpler\Components\Database\Fixtures;

use Simpler\Components\Database\DB;
use Simpler\Components\Database\Fixtures\Interfaces\FixtureInterface;

abstract class Fixture extends DB implements FixtureInterface
{
    /**
     * @return void
     */
    abstract public function run(): void;
}
