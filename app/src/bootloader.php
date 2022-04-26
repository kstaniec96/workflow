<?php
/**
 * This file is a central file that
 * loads all resources.
 *
 * @package Simpler
 * @version 2.0
 *
 * @author Kamil Staniec
 */

use Project\Providers\ProjectProviders;
use Simpler\Components\Auth\Auth;
use Simpler\Components\Database\DB;
use Simpler\Components\Exceptions\HandlerException;
use Simpler\Components\Http\Routers\View;
use Simpler\Components\Localization;
use Simpler\Components\Security\CSP;
use Simpler\Components\Security\Csrf;
use Carbon\Carbon;

// Checking the current PHP version.
if (PHP_VERSION_ID < 70300) {
    @ob_end_clean();
    throw new RuntimeException("PHP version isn't high enough! The minimum PHP version is 7.3.0");
}

// Included composer component
require ROOT_PATH.'/vendor/autoload.php';

// Set default timezone
Localization::setTimezone();

if (!isConsole()) {
    // Register whoops library.
    HandlerException::whoops();
}

// Init env file
$dotenv = Dotenv\Dotenv::createImmutable(ROOT_PATH);
$dotenv->load();

// DB Init
if (env('DB_ENABLED')) {
    DB::init();
}

if (!isConsole()) {
    // Start session
    session()->start();

    // CSP init
    if (env('CSP_ENABLED')) {
        CSP::init();
    }

    // Set carbon locale
    Carbon::setLocale(locale());

    // Csrf init
    Csrf::init();

    // Refresh auth session
    Auth::refresh();

    // View init
    View::init();

    // Project providers call.
    container(ProjectProviders::class)->call('handle');
}
