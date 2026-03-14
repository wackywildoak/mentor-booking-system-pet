<?php

return [
    'db' => [
        'host' => $_ENV['DB_HOST'],
        'port' => $_ENV['DB_PORT'],
        'name' => $_ENV['DB_NAME'],
        'user' => $_ENV['DB_USER'],
        'password' => $_ENV['DB_PASSWORD'],
    ],

    'jwt' => [
        'secret' => $_ENV['JWT_SECRET'],
    ],

    'app' => [
        'env' => $_ENV['APP_ENV'] ?? 'dev',
        'debug' => $_ENV['APP_DEBUG'] === 'true',
        'url' => $_ENV['APP_URL'] ?? 'http://localhost',
    ]
];