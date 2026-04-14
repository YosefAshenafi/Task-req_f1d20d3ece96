<?php

return [
    // Command registration
    'commands' => [
        \app\command\AutoCancelOrders::class,
        \app\command\IndexCleanup::class,
    ],
];
