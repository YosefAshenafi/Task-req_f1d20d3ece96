<?php

return [
    // Application name
    'app_name' => 'CampusOps',

    // Debug mode
    'app_debug' => env('APP_DEBUG', false),

    // Application host
    'app_host' => '',

    // Default timezone
    'default_timezone' => 'UTC',

    // Default language
    'default_lang' => 'en',

    // Exception handling — only show detailed errors when APP_DEBUG is true
    'show_error_msg' => env('APP_DEBUG', false),

    // URL settings
    'with_route' => true,
    'url_route_must' => false,
];
