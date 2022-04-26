<?php

namespace Simpler\Components\Interfaces;

interface LocalizationInterface
{
    /**
     * @param string $index
     * @param array $bind
     * @param bool $nullable
     * @return null|string
     */
    public static function lang(string $index, array $bind = [], bool $nullable = false): ?string;

    /**
     * @param string|null $locale
     * @return bool
     */
    public static function setLocale(?string $locale = null): bool;

    /**
     * @return string
     */
    public static function getLocale(): string;

    /**
     * @param string|null $timezone
     * @return bool
     */
    public static function setTimezone(?string $timezone = null): bool;

    /**
     * @return string
     */
    public static function getTimezone(): string;
}
