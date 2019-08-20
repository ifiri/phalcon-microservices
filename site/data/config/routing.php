<?php

use App\Controllers;

return [
    [
        'prefix' => '/',
        'handler' => Controllers\RootController::class,
        'endpoints' => [
            '/' => [
                'method' => 'get',
                'handler' => 'index',
            ],
        ],
    ],
    [
        'prefix' => '/auth',
        'handler' => Controllers\AuthController::class,
        'endpoints' => [
            '/signin' => [
                'method' => 'get',
                'handler' => 'signin',
            ],
        ],
    ],
    [
        'prefix' => '/api',
        'handler' => Controllers\ApiController::class,
        'endpoints' => [
            '/signin' => [
                'method' => 'post',
                'handler' => 'signin',
            ],
        ],
    ],
];