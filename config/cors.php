<?php

return [
    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    // Allow all origins in local dev. For production, set specific origins.
    'allowed_origins' => ['*'],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'allowed_methods' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => false,
];
