
# GitHub Webhook Deployer

A Laravel package for handling GitHub webhook events and automatically triggering deployment or custom scripts.

## Features

- Listens for GitHub `push` webhook events.
- Supports multi-project setup.
- Allows custom scripts to be executed per project.
- Secure secret validation.
- Easy to install and configure.

## Requirements

- Laravel 11+
- PHP 8.1+
- Composer

## Installation

Require the package using Composer:

```bash
composer require nishtman/github-webhook-deployer
```

Publish the config file:

```bash
php artisan vendor:publish --tag=github-webhook-config
```

Publish the migration file and run it:

```bash
php artisan vendor:publish --tag=github-webhook-migrations
php artisan migrate
```

## Configuration

Once published, you can configure your webhook settings in `config/github-webhook.php`.

Example config:

```php
return [
    'secret' => env('GITHUB_WEBHOOK_SECRET'),
    'projects' => [
        'your-project-name' => [
            'path' => base_path(),
            'commands' => [
                'git pull origin main',
                'php artisan migrate --force',
                'php artisan config:clear',
                'php artisan cache:clear',
            ],
        ],
    ],
];
```

Make sure to set the `GITHUB_WEBHOOK_SECRET` value in your `.env` file.

## Webhook Setup

- In your GitHub repository, go to **Settings > Webhooks**.
- Add a new webhook with the URL: `https://yourdomain.com/github-webhook`
- Choose `application/json` as the content type.
- Provide the same secret token used in `.env`.

## Artisan Commands

The package provides the following Artisan commands:

```bash
php artisan webhook:list       # List all webhook projects
php artisan webhook:test       # Test webhook execution for a project
```

## Security Notes

- Ensure the `GITHUB_WEBHOOK_SECRET` matches between GitHub and your `.env`.
- Protect the webhook route using middleware if needed.

## Contributing

Feel free to submit issues or PRs to improve this package!

## License

MIT License. See the [LICENSE](LICENSE) file for details.