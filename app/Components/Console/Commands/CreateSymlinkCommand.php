<?php
/**
 * Command for creates a symbolic link.
 */

namespace Simpler\Components\Console\Commands;

use Simpler\Components\Console\Command;
use Simpler\Components\Facades\Dir;
use Exception;

class CreateSymlinkCommand extends Command
{
    /**
     * @return int
     */
    public function handle(): int
    {
        try {
            $target = $this->getArgv()[0];
            $link = $this->getArgv()[1] ?? '';

            if (strpos($target, 'storage') !== false) {
                $target = 'storage/project';
                $link = 'storage';
            }

            $target = basePath($target);
            $link = basePath('public/'.$link);

            if (Dir::symlink($target, $link)) {
                $this->info('Symlink process successfully completed: '.$target.' -> '.$link);
            }
        } catch (Exception $e) {
            $this->error($e->getMessage());
        }

        return 0;
    }
}
