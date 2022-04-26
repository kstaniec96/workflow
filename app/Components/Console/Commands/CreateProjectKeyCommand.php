<?php
/**
 * Command for create Project key.
 */

namespace Simpler\Components\Console\Commands;

use RuntimeException;
use Simpler\Components\Console\Command;
use Exception;
use Simpler\Components\Facades\File;
use Simpler\Utils\StringUtil;

class CreateProjectKeyCommand extends Command
{
    /**
     * @return int
     */
    public function handle(): int
    {
        try {
            if (File::has(storagePath('jwt-secret.txt'))) {
                throw new RuntimeException('The project key has already been generated');
            }

            $secret = StringUtil::randSecure(128);
            File::put(storagePath('project-secret.key'), $secret);

            $this->info('Secret key: '.$secret);
            $this->success('Secret key generated -> '.storagePath('project-secret.key'));
        } catch (Exception $e) {
            $this->error($e->getMessage());
        }

        return 0;
    }
}
