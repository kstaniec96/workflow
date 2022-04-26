<?php
/**
 * Command for create JWT secret key.
 */

namespace Simpler\Components\Console\Commands;

use Simpler\Components\Console\Command;
use Exception;
use Simpler\Components\Facades\File;
use Simpler\Utils\StringUtil;

class CreateJWTSecretCommand extends Command
{
    /** @var string */
    private const JWT_FILE = 'jwt-secret.key';

    /**
     * @return int
     */
    public function handle(): int
    {
        try {
            if (File::has(storagePath(self::JWT_FILE))) {
                $this->warning('The secret string has already been generated');
            }

            $secret = StringUtil::randSecure(128);
            File::put(storagePath(self::JWT_FILE), $secret);

            $this->info('Secret key: '.$secret);
            $this->success('Secret key generated -> '.storagePath(self::JWT_FILE));
        } catch (Exception $e) {
            $this->error($e->getMessage());
        }

        return 0;
    }
}
