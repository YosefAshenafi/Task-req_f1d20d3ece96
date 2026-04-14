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

        // User management routes
        Route::group('users', function () {
            Route::get('', 'UserController/index')
                ->middleware('rbac', 'users.read');
            Route::get('/:id', 'UserController/show')
                ->middleware('rbac', 'users.read');
            Route::post('', 'UserController/create')
                ->middleware('rbac', 'users.create');
            Route::put('/:id', 'UserController/update')
                ->middleware('rbac', 'users.update');
            Route::delete('/:id', 'UserController/delete')
                ->middleware('rbac', 'users.delete');
            Route::put('/:id/role', 'UserController/changeRole')
                ->middleware('rbac', 'users.update');
            Route::put('/:id/password', 'UserController/resetPassword')
                ->middleware('rbac', 'users.password');
        });

        // Activity management routes
        Route::group('activities', function () {
            Route::get('', 'ActivityController/index');
            Route::get('/:id', 'ActivityController/show');
            Route::get('/:id/versions', 'ActivityController/versions')
                ->middleware('rbac', 'activities.read');
            Route::get('/:id/signups', 'ActivityController/signups');
            Route::get('/:id/change-log', 'ActivityController/changeLog')
                ->middleware('rbac', 'activities.read');
            Route::post('', 'ActivityController/create')
                ->middleware('rbac', 'activities.create');
            Route::put('/:id', 'ActivityController/update')
                ->middleware('rbac', 'activities.update');
            Route::post('/:id/publish', 'ActivityController/publish')
                ->middleware('rbac', 'activities.publish');
            Route::post('/:id/start', 'ActivityController/start')
                ->middleware('rbac', 'activities.transition');
            Route::post('/:id/complete', 'ActivityController/complete')
                ->middleware('rbac', 'activities.transition');
            Route::post('/:id/archive', 'ActivityController/archive')
                ->middleware('rbac', 'activities.transition');
            Route::post('/:id/signups', 'ActivityController/signup');
            Route::delete('/:id/signups/:signup_id', 'ActivityController/cancelSignup');
            Route::post('/:id/signups/:signup_id/acknowledge', 'ActivityController/acknowledge');
        });

        // Order management routes
        Route::group('orders', function () {
            Route::get('', 'OrderController/index');
            Route::get('/:id', 'OrderController/show');
            Route::get('/:id/history', 'OrderController/history');
            Route::post('', 'OrderController/create')
                ->middleware('rbac', 'orders.create');
            Route::put('/:id', 'OrderController/update')
                ->middleware('rbac', 'orders.update');
            Route::post('/:id/initiate-payment', 'OrderController/initiatePayment')
                ->middleware('rbac', 'orders.payment');
            Route::post('/:id/confirm-payment', 'OrderController/confirmPayment')
                ->middleware('rbac', 'orders.payment');
            Route::post('/:id/start-ticketing', 'OrderController/startTicketing')
                ->middleware('rbac', 'orders.ticketing');
            Route::post('/:id/ticket', 'OrderController/ticket')
                ->middleware('rbac', 'orders.ticketing');
            Route::post('/:id/refund', 'OrderController/refund')
                ->middleware('rbac', 'orders.refund');
            Route::post('/:id/cancel', 'OrderController/cancel')
                ->middleware('rbac', 'orders.cancel');
            Route::post('/:id/close', 'OrderController/close')
                ->middleware('rbac', 'orders.close');
            Route::put('/:id/address', 'OrderController/updateAddress')
                ->middleware('rbac', 'orders.update');
        });

        // Shipment routes
        Route::group('orders/:order_id/shipments', function () {
            Route::get('', 'ShipmentController/index');
            Route::post('', 'ShipmentController/create')
                ->middleware('rbac', 'shipments.create');
        });

        Route::group('shipments', function () {
            Route::get('/:id', 'ShipmentController/show');
            Route::post('/:id/scan', 'ShipmentController/scan');
            Route::get('/:id/scan-history', 'ShipmentController/scanHistory');
            Route::post('/:id/confirm-delivery', 'ShipmentController/confirmDelivery')
                ->middleware('rbac', 'shipments.deliver');
            Route::get('/:id/exceptions', 'ShipmentController/exceptions');
            Route::post('/:id/exceptions', 'ShipmentController/reportException')
                ->middleware('rbac', 'shipments.exception');
        });

        // Violation management routes
        Route::group('violations', function () {
            Route::get('rules', 'ViolationController/rules')
                ->middleware('rbac', 'violations.read');
            Route::get('rules/:id', 'ViolationController/ruleShow')
                ->middleware('rbac', 'violations.read');
            Route::post('rules', 'ViolationController/ruleCreate')
                ->middleware('rbac', 'violations.rules');
            Route::put('rules/:id', 'ViolationController/ruleUpdate')
                ->middleware('rbac', 'violations.rules');
            Route::delete('rules/:id', 'ViolationController/ruleDelete')
                ->middleware('rbac', 'violations.rules');
            Route::get('', 'ViolationController/index');
            Route::get('/:id', 'ViolationController/show');
            Route::post('', 'ViolationController/create')
                ->middleware('rbac', 'violations.create');
            Route::get('user/:user_id', 'ViolationController/userViolations');
            Route::get('group/:group_id', 'ViolationController/groupViolations');
            Route::post('/:id/appeal', 'ViolationController/appeal');
            Route::post('/:id/review', 'ViolationController/review')
                ->middleware('rbac', 'violations.review');
            Route::post('/:id/final-decision', 'ViolationController/finalDecision')
                ->middleware('rbac', 'violations.review');
        });

        // File upload routes
        Route::group('upload', function () {
            Route::post('', 'UploadController/upload')
                ->middleware('rbac', 'uploads.create');
            Route::get('/:id', 'UploadController/show');
            Route::get('/:id/download', 'UploadController/download');
            Route::delete('/:id', 'UploadController/delete')
                ->middleware('rbac', 'uploads.delete');
        });

        // Task routes
        Route::group('activities/:activity_id/tasks', function () {
            Route::get('', 'TaskController/index');
            Route::post('', 'TaskController/create')
                ->middleware('rbac', 'tasks.create');
        });

        Route::group('tasks', function () {
            Route::put('/:id', 'TaskController/update')
                ->middleware('rbac', 'tasks.update');
            Route::put('/:id/status', 'TaskController/updateStatus');
            Route::delete('/:id', 'TaskController/delete')
                ->middleware('rbac', 'tasks.delete');
        });

        // Checklist routes
        Route::group('activities/:activity_id/checklists', function () {
            Route::get('', 'ChecklistController/index');
            Route::post('', 'ChecklistController/create')
                ->middleware('rbac', 'tasks.create');
        });

        Route::group('checklists', function () {
            Route::put('/:id', 'ChecklistController/update')
                ->middleware('rbac', 'tasks.update');
            Route::delete('/:id', 'ChecklistController/delete')
                ->middleware('rbac', 'tasks.delete');
            Route::post('/:id/items/:item_id/complete', 'ChecklistController/completeItem');
        });

        // Staffing routes
        Route::group('activities/:activity_id/staffing', function () {
            Route::get('', 'StaffingController/index');
            Route::post('', 'StaffingController/create')
                ->middleware('rbac', 'staffing.create');
        });

        Route::group('staffing', function () {
            Route::put('/:id', 'StaffingController/update')
                ->middleware('rbac', 'staffing.update');
            Route::delete('/:id', 'StaffingController/delete')
                ->middleware('rbac', 'staffing.delete');
        });

        // Search routes
        Route::group('search', function () {
            Route::get('', 'SearchController/index');
            Route::get('suggest', 'SearchController/suggest');
            Route::get('logistics', 'SearchController/logistics');
        });

        Route::group('index', function () {
            Route::get('status', 'SearchController/status')
                ->middleware('rbac', 'admin');
            Route::post('rebuild', 'SearchController/rebuild')
                ->middleware('rbac', 'admin');
            Route::post('cleanup', 'SearchController/cleanup')
                ->middleware('rbac', 'admin');
        });

        // Notification routes
        Route::group('notifications', function () {
            Route::get('', 'NotificationController/index');
            Route::put('/:id/read', 'NotificationController/markRead');
            Route::get('settings', 'NotificationController/settings');
            Route::put('settings', 'NotificationController/updateSettings');
        });

        // Preferences routes
        Route::group('preferences', function () {
            Route::get('', 'PreferenceController/index');
            Route::put('', 'PreferenceController/update');
        });

        // Recommendation routes
        Route::group('recommendations', function () {
            Route::get('', 'RecommendationController/index');
            Route::get('popular', 'RecommendationController/popular');
        });

        // Dashboard routes
        Route::group('dashboard', function () {
            Route::get('', 'DashboardController/index');
            Route::get('custom', 'DashboardController/custom');
            Route::post('custom', 'DashboardController/createCustom')
                ->middleware('rbac', 'dashboard.create');
            Route::put('custom/:id', 'DashboardController/updateCustom')
                ->middleware('rbac', 'dashboard.update');
        });

        // Audit trail routes
        Route::group('audit', function () {
            Route::get('', 'AuditController/index')
                ->middleware('rbac', 'audit.read');
        });
    })->middleware('auth');

})->allowCrossDomain();
