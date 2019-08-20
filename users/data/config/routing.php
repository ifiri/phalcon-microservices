<?php

use App\Controllers;

return [
    [
        'prefix' => '/api',
        'handler' => Controllers\ApiController::class,
        'endpoints' => [
            '/user/exists' => [
                'method' => 'post',
                'handler' => 'isUserExists',
            ],
        ],
    ],
];