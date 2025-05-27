<?php

return [
    'webhook_secret' => env('GITHUB_WEBHOOK_SECRET', ''),

    'repository_source' => env('WEBHOOK_REPOSITORY_SOURCE', 'config'),

    'repositories' => [
        'nishtman1366/astan-backend' => [
            'main' => [
                'path' => '/var/www/jask-backend',
                'commands' => [
                    'pull' => 'git pull origin main 2>&1',
                    'composer' => 'composer install --no-dev --optimize-autoloader 2>&1',
                    'cache' => 'php artisan config:cache 2>&1',
                    'migrate' => 'php artisan migrate --force 2>&1',
                ],
            ],
            'dev' => [
                'path' => '/var/www/test/jask-backend',
                'commands' => [
                    'pull' => 'git pull origin dev 2>&1',
                    'composer' => 'composer install --no-dev --optimize-autoloader 2>&1',
                    'cache' => 'php artisan config:cache 2>&1',
                    'migrate' => 'php artisan migrate --force 2>&1',
                ],
            ],
        ],
        // more ...
    ],
];
