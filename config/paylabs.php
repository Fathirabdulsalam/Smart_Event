<?php

return [
    'server' => env('PAYLABS_SERVER', 'SIT'),
    'version' => env('PAYLABS_VERSION', 'v2.3'),

    'merchant_id' => env('PAYLABS_MERCHANT_ID', env('PAYLABS_MID', env('MID', ''))),

    'notify_url' => env('PAYLABS_NOTIFY_URL', ''),

    'base_url' => [
        'SIT' => env('PAYLABS_BASE_URL_SIT', env('PAYLABS_BASE_URL', '')),
        'PROD' => env('PAYLABS_BASE_URL_PROD', ''),
    ],

    'private_key' => env('PAYLABS_PRIVATE_KEY', env('PRIVATE_KEY', '')),
    'public_key' => env('PAYLABS_PUBLIC_KEY', env('PUBLIC_KEY', '')),

    'private_key_file' => env('PAYLABS_PRIVATE_KEY_FILE', ''),
    'public_key_file' => env('PAYLABS_PUBLIC_KEY_FILE', ''),
];
