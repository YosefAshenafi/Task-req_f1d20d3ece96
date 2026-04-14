<?php

// Global middleware configuration
return [
    // Global middleware
    'global' => [],

    // Alias middleware
    'alias' => [
        'auth' => app\middleware\AuthMiddleware::class,
        'rbac' => app\middleware\RbacMiddleware::class,
        'rate_limit' => app\middleware\RateLimitMiddleware::class,
        'sensitive_data' => app\middleware\SensitiveDataMiddleware::class,
    ],
];
