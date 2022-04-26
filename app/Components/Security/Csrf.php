<?php
/**
 * This class of management of CSRF security.
 *
 * @package Simpler
 * @version 2.0
 * @use https://github.com/gilbitron/EasyCSRF
 */

namespace Simpler\Components\Security;

use Simpler\Components\Exceptions\InvalidCsrfException;
use Simpler\Components\Security\Interfaces\CsrfInterface;
use Simpler\Utils\StringUtil;
use Exception;

class Csrf implements CsrfInterface
{
    /**
     * Initial csrf security.
     *
     * @return void
     */
    public static function init(): void
    {
        (new self())->newInit();
    }

    /**
     * Check the token is valid.
     *
     * @return void
     */
    public static function check(): void
    {
        try {
            (new self())->newCheck();
        } catch (Exception $e) {
            throw new InvalidCsrfException($e->getMessage());
        }
    }

    /**
     * Get generate CSRF token.
     *
     * @return string
     */
    public static function getToken(): string
    {
        return session()->get((new self())->getTokenName(), '/[a-zA-Z0-9]+/') ?? '';
    }

    /**
     * Instance for check method.
     *
     * @return void
     */
    private function newCheck(): void
    {
        $headers = response()->headers();

        if (empty($headers['Postman-Token'] ?? '')) {
            $token = $headers['X-Csrf-Token'] ?? request()->get('csrf_token');

            if (empty($token)) {
                throw new InvalidCsrfException();
            }

            $sessionToken = self::getToken();

            if (empty($sessionToken)) {
                throw new InvalidCsrfException('Invalid CSRF session token');
            }

            if ($this->referralHash() !== substr(base64_decode($sessionToken), 10, 40)) {
                throw new InvalidCsrfException();
            }

            if ($token !== $sessionToken) {
                throw new InvalidCsrfException();
            }
        }
    }

    /**
     * Instance for init method.
     *
     * @return void
     */
    private function newInit(): void
    {
        session()->create($this->getTokenName(), $this->createToken(), '2 hours');
    }

    /**
     * Get CSRF token name.
     *
     * @return string
     */
    private function getTokenName(): string
    {
        return 'auth.csrf_token_'.projectKey();
    }

    /**
     * Create a new token.
     *
     * @return string
     */
    private function createToken(): string
    {
        return base64_encode(time().$this->referralHash().StringUtil::randSecure());
    }

    /**
     * Return a unique referral hash.
     *
     * @return string
     */
    private function referralHash(): string
    {
        if (empty($_SERVER['HTTP_USER_AGENT'])) {
            return sha1($_SERVER['REMOTE_ADDR']);
        }

        return sha1($_SERVER['REMOTE_ADDR'].$_SERVER['HTTP_USER_AGENT']);
    }
}
