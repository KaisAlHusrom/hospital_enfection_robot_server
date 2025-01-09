<?php

return [
    /*
    |--------------------------------------------------------------------------
    | API Configuration
    |--------------------------------------------------------------------------
    */

    // API Version
    'version' => '1.0',

    // Default Response Format
    'format' => [
        'success' => true,
        'message' => '',
        'data' => null,
    ],

    // Rate Limiting
    'throttle' => [
        // Default rate limiting
        'default' => [
            'attempts' => 60,
            'per_minutes' => 1,
        ],

        // Different limits for different types of users
        'authenticated' => [
            'attempts' => 120,
            'per_minutes' => 1,
        ],

        'guest' => [
            'attempts' => 30,
            'per_minutes' => 1,
        ],
    ],

    // Response Headers
    'enable_cors' => true,
    'headers' => [
        'cors' => [
            'allowed_origins' => ['*'],
            'allowed_methods' => ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS'],
            'allowed_headers' => ['Content-Type', 'Authorization', 'X-Requested-With'],
            'exposed_headers' => [],
            'max_age' => 0,
            'supports_credentials' => true,
        ],

        'security' => [
            'content-type-options' => 'nosniff',
            'frame-options' => 'DENY',
            'xss-protection' => '1; mode=block',
        ],

        'cache' => [
            'control' => 'no-cache, must-revalidate',
            'pragma' => 'no-cache',
        ],
    ],

    // Error Handling
    'errors' => [
        'include_trace' => env('APP_DEBUG', false),
        'trace_limit' => 10,
        'messages' => [
            'default' => 'An error occurred',
            'validation' => 'The given data was invalid',
            'not_found' => 'Resource not found',
            'unauthorized' => 'Unauthorized',
            'forbidden' => 'Forbidden',
            'server_error' => 'Server Error',
        ],
    ],

    // Pagination
    'pagination' => [
        'default_limit' => 15,
        'max_limit' => 100,
    ],

    // Routes
    'routes' => [
        'prefix' => 'api',
        'middleware' => ['api'],
        'version_prefix' => 'v1',
    ],
];
