<?php
/**
 * This class security for CSP.
 *
 * @package Simpler
 * @subpackage Security
 * @version 2.0
 */

namespace Simpler\Components\Security;

use Simpler\Components\Security\Interfaces\CSPInterface;
use Bepsvpt\SecureHeaders\SecureHeaders;
use Exception;
use RuntimeException;

class CSP implements CSPInterface
{
    /** @var string */
    private static string $nonce = '';

    /** @var string */
    private const CONFIG_FILE = ROOT_PATH.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'secure-headers.php';

    /**
     * Init CSP
     *
     * @return void
     */
    public static function init(): void
    {
        (new self())->newInit();
    }

    /**
     * Get CSP nonce.
     *
     * @return string
     */
    public static function nonce(): string
    {
        return self::$nonce;
    }

    /**
     * Object instance init method
     *
     * @return void
     */
    private function newInit(): void
    {
        try {
            self::$nonce = SecureHeaders::nonce();
            $secureHeaders = SecureHeaders::fromFile(self::CONFIG_FILE);

            // Get headers
            $secureHeaders->headers();

            // Send headers to HTTP response
            $secureHeaders->send();
        } catch (Exception $e) {
            throw new RuntimeException($e->getMessage());
        }
    }
}
