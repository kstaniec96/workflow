<?php
/**
 * Command create service class or service/interface class.
 */

namespace Simpler\Components\Console\Commands\Stubs;

use Simpler\Components\Console\Command;
use Simpler\Components\Stubs;

class CreateServiceCommand extends Command
{
    /**
     * @return int
     */
    public function handle(): int
    {
        $service = $this->getArgv(0);
        $replace = [''];

        $isInterface = !empty($this->getOptions('interface'));
        $interfaceName = str_replace('Service', '', $service).'Interface';

        if ($isInterface) {
            $namespace = Stubs::getNamespace($service);
            $className = Stubs::getClassName($interfaceName);

            $replace = [
                'use Project\Interfaces\Services'.$namespace.'\\'.$className.';',
                'implements '.$className,
            ];
        }

        // Create service class
        $this->stubGenerator('service', SERVICES_PATH, null, [
            'search' => ['{{ use }}', '{{ interface }}'],
            'replace' => $replace,
        ]);

        if ($isInterface) {
            // Create interface class
            $this->stubGenerator('interface', INTERFACES_PATH, 'Services/'.$interfaceName);
        }

        return 0;
    }
}
