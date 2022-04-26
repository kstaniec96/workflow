<?php
/**
 * This class of management Console.
 *
 * @package Simpler
 * @version 2.0
 */

namespace Simpler\Components\Console;

use Simpler\Components\Console\Commands\CreateJWTSecretCommand;
use Simpler\Components\Console\Commands\CreateProjectKeyCommand;
use Simpler\Components\Console\Commands\CreateSymlinkCommand;
use Simpler\Components\Console\Commands\Stubs\CreateConsoleCommand;
use Simpler\Components\Console\Commands\Stubs\CreateControllerCommand;
use Simpler\Components\Console\Commands\Stubs\CreateExceptionCommand;
use Simpler\Components\Console\Commands\Stubs\CreateInterfaceCommand;
use Simpler\Components\Console\Commands\Stubs\CreateMiddlewareCommand;
use Simpler\Components\Console\Commands\Stubs\CreateModelCommand;
use Simpler\Components\Console\Commands\Stubs\CreateServiceCommand;
use Simpler\Components\Console\Commands\Stubs\CreateTestCommand;
use Simpler\Components\Console\Commands\Stubs\CreateValidatorCommand;
use Simpler\Components\Database\Commands\CreateFactoryCommand;
use Simpler\Components\Database\Commands\Fixture\FixtureCreateCommand;
use Simpler\Components\Database\Commands\Fixture\FixtureRunCommand;
use Simpler\Components\Database\Commands\Migration\MigrateDownCommand;
use Simpler\Components\Database\Commands\Migration\MigrateUpCommand;
use Simpler\Components\Database\Commands\Migration\MigrationCreateCommand;
use Simpler\Components\Interfaces\ConsoleInterface;
use Simpler\Components\Stubs;

abstract class Console implements ConsoleInterface
{
    /** @var array */
    protected array $commands = [];

    /** @var array|string[] */
    private array $defaultCommands = [
        // Migration commands
        'migration:create' => MigrationCreateCommand::class,
        'migrate:up' => MigrateUpCommand::class,
        'migrate:down' => MigrateDownCommand::class,

        // Fixture command
        'fixture:create' => FixtureCreateCommand::class,
        'fixture:run' => FixtureRunCommand::class,

        // Factory command
        'factory:create' => CreateFactoryCommand::class,

        // Stubs create commands
        'controller:create' => CreateControllerCommand::class,
        'model:create' => CreateModelCommand::class,
        'command:create' => CreateConsoleCommand::class,
        'middleware:create' => CreateMiddlewareCommand::class,
        'validator:create' => CreateValidatorCommand::class,
        'service:create' => CreateServiceCommand::class,
        'interface:create' => CreateInterfaceCommand::class,
        'test:create' => CreateTestCommand::class,
        'exception:create' => CreateExceptionCommand::class,

        // Others
        'symlink' => CreateSymlinkCommand::class,
        'jwt:secret' => CreateJWTSecretCommand::class,
        'project:key' => CreateProjectKeyCommand::class,
    ];

    /** @var array */
    private static array $options = [];

    /** @var array */
    private static array $argv = [];

    /**
     * Initial console methods.
     *
     * @param array|null $argv
     * @return void
     */
    public static function init(?array $argv): void
    {
        (new static())->newInit($argv);
    }

    /**
     * Get all options from console.
     *
     * @param string|null $option
     * @return array|mixed|string|null
     */
    public function getOptions(?string $option = null)
    {
        if (is_null($option)) {
            return self::$options;
        }

        $getOption = self::$options[$option] ?? '';

        if (empty($getOption)) {
            return null;
        }

        return $getOption;
    }

    /**
     * Get all argv from console.
     *
     * @param string|null $argv
     * @return array|mixed|string|null
     */
    public function getArgv(?string $argv = null)
    {
        if (is_null($argv)) {
            return self::$argv;
        }

        $getArgv = self::$argv[$argv] ?? '';

        if (empty($getArgv)) {
            return null;
        }

        return $getArgv;
    }

    /**
     * View info message in console.
     *
     * @param string $str
     * @return void
     */
    public function info(string $str): void
    {
        echo "\033[36m$str \033[0m\n";
    }

    /**
     * View success message in console.
     *
     * @param string $str
     * @return void
     */
    public function success(string $str): void
    {
        echo "\033[32m$str \033[0m\n";
    }

    /**
     * View warning message in console.
     *
     * @param string $str
     * @return void
     */
    public function warning(string $str): void
    {
        echo "\033[33m$str \033[0m\n";
    }

    /**
     * View error message in console.
     *
     * @param string $str
     * @return void
     */
    public function error(string $str): void
    {
        echo "\033[31m$str \033[0m\n";
        exit;
    }

    /**
     * Generate class structure from stub.
     *
     * @param string $stubTemplate
     * @param string $savePath
     * @param string|null $argv
     * @param array $stubExtensions
     * @return void
     */
    protected function stubGenerator(
        string $stubTemplate,
        string $savePath,
        ?string $argv = null,
        array $stubExtensions = []
    ): void {
        $argv = $argv ?? $this->getArgv(0);

        if (empty($argv)) {
            $this->error('You must provide a stub class name');
        }

        $stub = Stubs::init($stubTemplate)
            ->setClassName($argv)
            ->setNamespace($argv);

        if (!empty($stubExtensions)) {
            $stub->setCustom($stubExtensions['search'], $stubExtensions['replace']);
        }

        $stubFile = $argv.'.php';
        $argv = basename($argv);

        $path = $savePath.DS.$stubFile;

        if (!$stub->save($path)) {
            $this->error('The '.$argv.' could not be created');
        } else {
            $this->success('The '.$argv.' has been created -> '.filterPath($path));
        }
    }

    /**
     * Instance method init.
     *
     * @param array|null $argv
     * @return void
     */
    private function newInit(?array $argv): void
    {
        if (!empty($argv)) {
            $commands = array_merge($this->commands, $this->defaultCommands);

            $command = $argv[1] ?? '';
            $className = $commands[$command] ?? '';

            if (empty($className)) {
                (new static())->warning('Command '.$command.' not found');
                exit;
            }

            if (class_exists($className)) {
                unset($argv[0], $argv[1]);

                self::$options = $this->splitOptions($argv);
                self::$argv = $this->splitArgv($argv);

                (new $className)->handle();
            } else {
                (new static())->error('Class '.$className.' does not exist');
                exit;
            }
        }
    }

    /**
     * Split options from argv console.
     *
     * @param array $options
     * @return array
     */
    private function splitOptions(array $options): array
    {
        if (!empty($options)) {
            $destOptions = [];

            foreach ($options as $option) {
                if (strpos($option, '--') !== false) {
                    $option = str_replace('--', '', $option);
                    $explode = explode('=', $option);

                    $destOptions[$explode[0]] = $explode[1] ?? true;
                }
            }

            return $destOptions;
        }

        return [];
    }

    /**
     * Split arguments from console.
     *
     * @param array $argv
     * @return array
     */
    private function splitArgv(array $argv): array
    {
        if (!empty($argv)) {
            $destArgv = [];

            foreach ($argv as $arg) {
                if (strpos($arg, '--') !== false) {
                    continue;
                }

                $destArgv[] = $arg ?? true;
            }

            return $destArgv;
        }

        return [];
    }
}
