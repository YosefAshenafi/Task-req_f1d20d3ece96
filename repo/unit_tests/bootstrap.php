<?php

declare(strict_types=1);

define('ROOT_PATH', dirname(__DIR__) . '/backend/');

require ROOT_PATH . 'vendor/autoload.php';

$app = new \think\App(ROOT_PATH);
$app->initialize();

// Override database config for test environment
$dbConfig = [
    'type'     => env('DB_TYPE', 'mysql'),
    'hostname' => env('DB_HOST', '127.0.0.1'),
    'database' => env('DB_NAME', 'campusops_test'),
    'username' => env('DB_USER', 'root'),
    'password' => env('DB_PASS', ''),
    'hostport' => env('DB_PORT', '3306'),
    'charset'  => 'utf8mb4',
    'prefix'   => '',
];

\think\facade\Db::setConfig([
    'default'     => 'mysql',
    'connections' => [
        'mysql' => $dbConfig,
    ],
]);
