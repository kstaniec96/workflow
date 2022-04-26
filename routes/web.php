<?php

use Simpler\Components\Http\Middlewares\AuthMiddleware;
use Simpler\Components\Http\Middlewares\GuestMiddleware;
use Simpler\Components\Http\Routers\Route;
use Project\Http\Controllers\Auth\LoginController;
use Project\Http\Controllers\Auth\LogoutController;
use Project\Http\Controllers\Auth\PasswordController;
use Project\Http\Controllers\Auth\RegisterController;
use Project\Http\Controllers\User\DefaultGroupsController;
use Project\Http\Controllers\User\FriendsController;
use Project\Http\Controllers\User\GroupsController;
use Project\Http\Controllers\User\HomeController;
use Project\Http\Controllers\User\ProfileController;
use Project\Http\Controllers\User\SettingsController;

/***** Guest routes *****/
Route::group([
    'middlewares' => [GuestMiddleware::class],
], static function () {
    // Home
    Route::web([
        'uri' => '/',
        'name' => 'home.index',
    ], [\Project\Http\Controllers\HomeController::class, 'index']);
});

/***** Auth routes *****/
Route::group([
    'prefix' => '/auth',
    'as' => 'auth.',
    'middlewares' => [GuestMiddleware::class],
], static function () {
    // Login
    Route::web([
        'uri' => '/login',
        'name' => 'login.index',
    ], [LoginController::class, 'index']);

    // Register
    Route::group(
        ['prefix' => '/register', 'as' => 'register.'],
        static function () {
            // First step
            Route::web([
                'uri' => '/',
                'name' => 'index',
            ], [RegisterController::class, 'index']);

            // Second step
            Route::web([
                'uri' => '/verify/{token}',
                'name' => 'verify',
            ], [RegisterController::class, 'verify']);
        }
    );

    // Password
    Route::group(
        ['prefix' => '/password', 'as' => 'password.'],
        static function () {
            // First step
            Route::web([
                'uri' => '/',
                'name' => 'index',
            ], [PasswordController::class, 'index']);

            // Second step
            Route::web([
                'uri' => '/verify/{token}',
                'name' => 'verify',
            ], [PasswordController::class, 'verify']);
        }
    );
});

/***** User routes *****/
Route::group([
    'prefix' => '/user',
    'as' => 'user.',
    'middlewares' => [AuthMiddleware::class],
], static function () {
    // Home
    Route::web([
        'uri' => '/',
        'name' => 'home.index',
    ], [HomeController::class, 'index']);

    // Settings
    Route::web([
        'uri' => '/settings',
        'name' => 'settings.index',
    ], [SettingsController::class, 'index']);

    // Profile
    Route::web([
        'uri' => '/profile',
        'name' => 'profile.index',
    ], [ProfileController::class, 'index']);

    // Groups
    Route::web([
        'uri' => '/groups',
        'name' => 'groups.index',
    ], [GroupsController::class, 'index']);

    // Friends
    Route::web([
        'uri' => '/friends',
        'name' => 'friends.index',
    ], [FriendsController::class, 'index']);

    // Default groups
    Route::web([
        'uri' => '/default-groups',
        'name' => 'default-groups.index',
    ], [DefaultGroupsController::class, 'index']);

    // Logout
    Route::web([
        'uri' => '/logout',
        'name' => 'logout',
    ], [LogoutController::class, 'logout']);
});
