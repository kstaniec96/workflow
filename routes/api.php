<?php

use Simpler\Components\Http\Middlewares\AuthApiMiddleware;
use Simpler\Components\Http\Routers\Route;
use Project\Http\Controllers\Auth\LoginController;
use Project\Http\Controllers\Auth\PasswordController;
use Project\Http\Controllers\Auth\RegisterController;

/***** Auth routes *****/
Route::group(
    [
        'prefix' => '/auth',
        'as' => 'auth.',
    ],
    static function () {
        // Login
        Route::api([
            'uri' => '/login',
            'name' => 'login.api',
        ], [LoginController::class, 'api']);

        // Register
        Route::group(
            ['prefix' => '/register', 'as' => 'register.'],
            static function () {
                // First step
                Route::api([
                    'uri' => '/',
                    'name' => 'init',
                ], [RegisterController::class, 'init']);

                // Second step
                Route::api([
                    'uri' => '/groups',
                    'name' => 'groups',
                ], [RegisterController::class, 'groups']);
            }
        );

        // Password
        Route::group(
            ['prefix' => '/password', 'as' => 'password.'],
            static function () {
                // First step
                Route::api([
                    'uri' => '/',
                    'name' => 'index',
                ], [PasswordController::class, 'init']);

                // Second step
                Route::api([
                    'uri' => '/change',
                    'name' => 'change',
                ], [PasswordController::class, 'change']);
            }
        );
    }
);

/***** User routes *****/
Route::group(
    [
        'prefix' => '/user',
        'as' => 'user.',
        'middlewares' => [AuthApiMiddleware::class],
    ],
    static function () {
        // Login
        Route::api([
            'uri' => '/home',
            'name' => 'home',
        ], static function () {
            dv('Hello world!');
        });
    }
);
