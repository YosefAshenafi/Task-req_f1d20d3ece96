<?php

use think\facade\Route;

// API v1 routes
Route::group('api/v1', function () {

    // Health check - no auth required
    Route::get('ping', 'Index/ping');

    // Auth routes - no auth required
    Route::post('auth/login', 'AuthController/login');

    // Auth routes - require authentication
    Route::group('', function () {
        Route::post('auth/logout', 'AuthController/logout');
        Route::post('auth/unlock', 'AuthController/unlock')
            ->middleware('rbac', 'users.password');
    })->middleware('auth');

})->allowCrossDomain();
