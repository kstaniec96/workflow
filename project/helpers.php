<?php

if (!function_exists('activeNavItem')) {
    /**
     * Check when menu item is active.
     *
     * @param string $routeName
     * @param string $activeClass
     * @return string
     */
    function activeNavItem(string $routeName, string $activeClass = 'active-menu-item'): string
    {
        return stripos(getCurrentRouteName(), $routeName) !== false ? $activeClass : '';
    }
}
