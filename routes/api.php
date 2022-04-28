<?php

use Project\Http\Controllers\User\DefaultGroupsController;
use Project\Http\Controllers\User\FriendsController;
use Project\Http\Controllers\User\GroupsController;
use Project\Http\Controllers\User\OwnGroups\OwnGroupsController;
use Project\Http\Controllers\User\Posts\PostCommentsController;
use Project\Http\Controllers\User\Posts\PostLikesController;
use Project\Http\Controllers\User\Posts\PostsController;
use Project\Http\Controllers\User\SettingsController;
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
        // Posts
        Route::group(
            ['prefix' => '/posts', 'as' => 'posts.'],
            static function () {
                // Create
                Route::api([
                    'uri' => '/create',
                    'name' => 'create',
                ], [PostsController::class, 'create']);

                // Edit
                Route::api([
                    'uri' => '/edit/{id}',
                    'name' => 'edit',
                ], [PostsController::class, 'edit']);

                // Delete
                Route::api([
                    'uri' => '/delete/{id}',
                    'name' => 'delete',
                    'method' => 'DELETE',
                ], [PostsController::class, 'delete']);
            }
        );

        // Comments
        Route::group(
            ['prefix' => '/comments', 'as' => 'comments.'],
            static function () {
                // Create
                Route::api([
                    'uri' => '/create/{id}',
                    'name' => 'create',
                ], [PostCommentsController::class, 'create']);

                // Edit
                Route::api([
                    'uri' => '/edit/{id}',
                    'name' => 'edit',
                ], [PostCommentsController::class, 'edit']);

                // Delete
                Route::api([
                    'uri' => '/delete/{id}',
                    'name' => 'delete',
                    'method' => 'DELETE',
                ], [PostCommentsController::class, 'delete']);
            }
        );

        // Likes
        Route::group(
            ['prefix' => '/likes', 'as' => 'likes.'],
            static function () {
                // Create
                Route::api([
                    'uri' => '/up/{id}',
                    'name' => 'up',
                ], [PostLikesController::class, 'up']);

                // Edit
                Route::api([
                    'uri' => '/down/{id}',
                    'name' => 'down',
                ], [PostLikesController::class, 'down']);
            }
        );

        // Settings
        Route::group(
            ['prefix' => '/settings', 'as' => 'settings.'],
            static function () {
                // Basic
                Route::api([
                    'uri' => '/update',
                    'name' => 'update',
                ], [SettingsController::class, 'update']);

                // Change password
                Route::api([
                    'uri' => '/change',
                    'name' => 'change',
                ], [SettingsController::class, 'change']);
            }
        );

        // Friends
        Route::group(
            ['prefix' => '/friends', 'as' => 'friends.'],
            static function () {
                // Add
                Route::api([
                    'uri' => '/add/{id}',
                    'name' => 'add',
                ], [FriendsController::class, 'add']);

                // Remove
                Route::api([
                    'uri' => '/remove/{id}',
                    'name' => 'remove',
                    'method' => 'DELETE',
                ], [FriendsController::class, 'remove']);
            }
        );

        // Groups
        Route::group(
            ['prefix' => '/groups', 'as' => 'groups.'],
            static function () {
                // Add
                Route::api([
                    'uri' => '/add/{id}',
                    'name' => 'add',
                ], [GroupsController::class, 'add']);

                // Remove
                Route::api([
                    'uri' => '/remove/{id}',
                    'name' => 'remove',
                    'method' => 'DELETE',
                ], [GroupsController::class, 'remove']);
            }
        );

        // Own groups
        Route::group(
            ['prefix' => '/own-groups', 'as' => 'own-groups.'],
            static function () {
                // Create
                Route::api([
                    'uri' => '/create',
                    'name' => 'create',
                ], [OwnGroupsController::class, 'create']);

                // Delete
                Route::api([
                    'uri' => '/delete/{id}',
                    'name' => 'delete',
                    'method' => 'DELETE',
                ], [OwnGroupsController::class, 'delete']);

                // Edit
                Route::api([
                    'uri' => '/edit/{id}',
                    'name' => 'edit',
                ], [OwnGroupsController::class, 'edit']);
            }
        );

        // Default groups
        Route::group(
            ['prefix' => '/default-groups', 'as' => 'default-groups.'],
            static function () {
                // Add
                Route::api([
                    'uri' => '/add/{id}',
                    'name' => 'add',
                ], [DefaultGroupsController::class, 'add']);

                // Remove
                Route::api([
                    'uri' => '/remove/{id}',
                    'name' => 'remove',
                    'method' => 'DELETE',
                ], [DefaultGroupsController::class, 'remove']);
            }
        );
    }
);
