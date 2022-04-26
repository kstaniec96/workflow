<?php
/**
 * This class is used to translate
 * the content of the page.
 *
 * @package Simpler
 * @version 2.0
 */

namespace Simpler\Components;

use Simpler\Components\Facades\Dir;
use Simpler\Components\Interfaces\LocalizationInterface;
use Exception;

class Localization implements LocalizationInterface
{
    /** @var string */
    private const SESSION = 'project.locale';

    /**
     * Translates the content of the page.
     *
     * @param string $index
     * @param array $bind
     * @param bool $nullable
     * @return null|string
     */
    public static function lang(string $index, array $bind = [], bool $nullable = false): ?string
    {
        $explodeIndex = explode('.', $index, 2);
        $index = $explodeIndex[1] ?? $index;

        // Get translations from php file.
        try {
            $data = import(RESOURCES_PATH.'/lang/'.self::getCurrentLocale().DS.$explodeIndex[0].'.php', true);
        } catch (Exception $e) {
            return $index;
        }

        // Get translation content.
        $content = DotNotation::set($index, $data) ?? ($nullable ? null : $index);

        if (empty($content)) {
            return null;
        }

        if (!empty($bind)) {
            $binder = [];

            foreach ($bind as $key => $value) {
                $binder['search'][] = ':'.$key;
                $binder['replace'][] = $value;
            }

            $content = str_replace($binder['search'], $binder['replace'], $content);
        }

        return $content;
    }

    /**
     * @param string|null $locale
     * @return bool
     */
    public static function setLocale(?string $locale = null): bool
    {
        return session()->create(
            self::SESSION,
            $locale ?? Config::get('app.locale.default'),
            '1 year'
        );
    }

    /**
     * @return string
     */
    public static function getLocale(): string
    {
        $default = Config::get('app.locale.default');

        if (session()->has(self::SESSION)) {
            return session(self::SESSION) ?? $default;
        }

        return response()->getHeader('Current-Locale') ?? $default;
    }

    /**
     * Set default timezone.
     *
     * @param string|null $timezone
     * @return bool
     */
    public static function setTimezone(?string $timezone = null): bool
    {
        return date_default_timezone_set($timezone ?? Config::get('app.locale.timezone'));
    }

    /**
     * Get default timezone.
     *
     * @return string
     */
    public static function getTimezone(): string
    {
        return date_default_timezone_get();
    }

    /**
     * Checks the browser language of the user who
     * is currently on the page.
     *
     * @return string
     */
    private static function getCurrentLocale(): string
    {
        $config = Config::get('app.locale');
        $default = $config['default'];

        if (!$config['test']) {
            $locale = self::getLocale();

            if (Dir::has(LANG_PATH.DS.$locale)) {
                return $locale;
            }
        }

        return $default;
    }
}
