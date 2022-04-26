<?php
/**
 * Manager for Cookie and Session class.
 *
 * @package Simpler
 * @subpackage HTTP
 * @version 2.0
 */

namespace Simpler\Components\Http\Providers;

use Simpler\Components\Config;
use Simpler\Components\Convert;
use Simpler\Components\DateTime;
use Simpler\Components\DotNotation;
use Simpler\Components\Http\Providers\Interfaces\ProviderManagerInterface;
use Simpler\Components\Http\Validator\Validator;
use Simpler\Components\Security\Filter;
use RuntimeException;

abstract class ProviderManager implements ProviderManagerInterface
{
    /** @var string */
    protected string $driver = '';

    /** @var array */
    protected array $config;

    /** @var string */
    protected const EXPIRES = 'expires';

    /** @var bool */
    private bool $flash = false;

    /** @var bool */
    private bool $isCookieProvider;

    public function __construct()
    {
        $this->config = Config::get($this->driver);
        $this->isCookieProvider = compare($this->driver, 'cookie');
    }

    /**
     * Checks whether an item provider exists.
     *
     * @param string $item
     * @param null|string $pattern
     * @return bool
     */
    public function has(string $item, ?string $pattern = null): bool
    {
        $provider = $this->getProviderItems();

        if (!empty($provider)) {
            if ($this->isCookieProvider) {
                $data = $provider[$item] ?? false;
            } else {
                [$group, $id] = $this->split($item);
                $data = $provider[$group][$id] ?? false;
            }

            if ($data === false) {
                return false;
            }

            if (!$this->isCookieProvider && $this->expire($item) < time()) {
                return false;
            }

            if ($pattern) {
                return Validator::validation($data, $pattern);
            }

            return true;
        }

        return false;
    }

    /**
     * Create a new provider item(s).
     *
     * @param array|string $item
     * @param mixed $value
     * @param string|null $expire
     * @return bool
     */
    public function create($item, $value = null, ?string $expire = null): bool
    {
        if (is_string($item)) {
            return $this->build($item, $value, $expire);
        }

        foreach ($item as $data) {
            $this->build($data[0], $data[1] ?? null, $data[2] ?? null);
        }

        return false;
    }

    /**
     * Provider item based flash messages.
     *
     * @param array|string $item
     * @param mixed $value
     * @return bool
     */
    public function flash($item, $value = null): bool
    {
        $this->flash = true;

        return $this->create($item, $value, $this->config['flash']['expire']);
    }

    /**
     * Refresh exists provider item.
     *
     * @param string $item
     * @param $value
     * @param string|null $expire
     * @return bool
     */
    public function refresh(string $item, $value = null, ?string $expire = null): bool
    {
        if ($this->has($item)) {
            $data = $this->get($item);
            $this->delete($item);

            return $this->build($item, $value ?? $data, $expire, true);
        }

        return false;
    }

    /**
     * Get all provider items or specific provider item from key.
     *
     * @param string|null $item
     * @return array
     */
    public function all(?string $item = null): array
    {
        $provider = $this->getProviderItems();
        unset($provider[session_name()]);

        if (!empty($item)) {
            return $provider[$item];
        }

        return $provider;
    }

    /**
     * Get provider item data.
     *
     * @param string $item
     * @param string|null $pattern
     * @return array|string|null
     */
    public function get(string $item, ?string $pattern = null)
    {
        if ($this->has($item, $pattern)) {
            $provider = $this->getProviderItems();

            if ($this->isCookieProvider) {
                $data = $provider[$item];
            } else {
                [$group, $id] = $this->split($item);
                $data = $provider[$group][$id];
            }

            $data = Filter::clear($data);

            if (DotNotation::check($data)) {
                return DotNotation::toArray($data);
            }

            return $data;
        }

        return null;
    }

    /**
     * Get provider item expire.
     *
     * @param string $item
     * @param bool $timestamp
     * @return mixed
     */
    public function expire(string $item, bool $timestamp = true)
    {
        if ($this->isCookieProvider) {
            throw new RuntimeException('Only for sessions');
        }

        [$group, $id] = $this->split($item);
        $expire = $this->getProviderItems()[self::EXPIRES][$group][$id] ?? 0;

        if (!$timestamp) {
            return DateTime::changeFormat($expire);
        }

        return $expire;
    }

    /**
     * Deletes a specific provider items.
     *
     * @param string $item
     * @return bool
     */
    public function delete(string $item): bool
    {
        // For only cookie provider
        if ($this->isCookieProvider) {
            return $this->cookie($item, '', Convert::toTimestamp('-100 years'));
        }

        // For only session provider
        $isOnce = DotNotation::check($item);

        if ($isOnce) {
            if ($this->has($item)) {
                [$group, $id] = $this->split($item);
                unset($_SESSION[$group][$id]);
            }
        } else {
            unset($_SESSION[$item]);
        }

        return $isOnce ? $this->has($item) : array_key_exists($item, $_SESSION);
    }

    /**
     * Get provider items.
     *
     * @return array
     */
    private function getProviderItems(): array
    {
        return $this->isCookieProvider ? ($_COOKIE ?? []) : ($_SESSION ?? []);
    }

    /**
     * Creates a specific one provider item.
     *
     * @param string $item
     * @param mixed $value
     * @param string|null $expire
     * @param bool $refresh
     * @return bool
     */
    private function build(string $item, $value = null, ?string $expire = null, bool $refresh = false): bool
    {
        if (!$refresh && $this->has($item)) {
            return true;
        }

        [$group, $item] = $this->split($item);

        // Validation provider item name.
        $this->validItem($item);

        // Validation provider item expire
        $expire = $expire ?? $this->config['expire'];
        $this->validExpire($expire);

        // Convert string time to timestamp.
        $timestamp = Convert::toTimestamp($expire);
        $value = DotNotation::toString(Filter::clear($value ?? 'empty'));

        // For only cookie provider
        if ($this->isCookieProvider) {
            return $this->cookie($item, $value, $timestamp);
        }

        // For only session provider
        $_SESSION[self::EXPIRES][$group][$item] = $timestamp;
        $_SESSION[$group][$item] = $value;

        return $this->has($item);
    }

    /**
     * Split provider item.
     *
     * @param string $name
     * @return array
     */
    private function split(string $name): array
    {
        $data = DotNotation::toArray($name);

        $group = $data[0];
        $name = $data[1] ?? null;
        $groupName = $group;

        if (is_null($name)) {
            $groupName = $this->flash ? $this->config['flash']['group'] : $this->config['group'];
        }

        $name = $name ?? $group;

        return [$groupName, $name, $groupName.'.'.$name];
    }

    /**
     * Create or delete cookie.
     *
     * @param string $cookie
     * @param string $value
     * @param int $expire
     * @return bool
     */
    private function cookie(string $cookie, string $value, int $expire): bool
    {
        return setcookie(
            $cookie,
            $value,
            $expire,
            $this->config['path'],
            $this->config['domain'],
            $this->config['secure'],
            $this->config['httponly']
        );
    }

    /**
     * Checks the entered provider item name.
     *
     * @param string $name
     * @return void
     */
    private function validItem(string $name): void
    {
        if (!preg_match('/^[a-z0-9-_]{1,128}$/i', $name)) {
            throw new RuntimeException('Incorrect provider item name: <q>'.$name.'</q>!');
        }
    }

    /**
     * Checks the entered provider item lifetime.
     *
     * @param string $expire
     * @return void
     */
    private function validExpire(string $expire): void
    {
        if (!preg_match(
            '/^[0-9 ]*(second|seconds|minute|minutes|hour|hours|day|days|month|months|year|years)$/',
            $expire
        )) {
            throw new RuntimeException('Incorrect provider item lifetime: <q>'.$expire.'</q>!');
        }
    }
}
