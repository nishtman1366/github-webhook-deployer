
# GitHub Webhook Deployer

A Laravel package for automated GitHub repository deployment via webhooks.

## Features

- Secure webhook handling with per-repository secrets
- Multi-repository and multi-branch support
- Custom deployment commands per branch
- Supports Laravel, Node.js (e.g., Next.js), and other environments

## Installation

1. Require the package via composer (when published):

```bash
composer require nishtman/github-webhook-deployer
```

2. Publish and run migrations:

```bash
php artisan vendor:publish --tag=github-webhook-deployer-migrations
php artisan migrate
```

3. Register the webhook route in `routes/webhook.php`:

```php
use Nishtman\GitHubWebhookDeployer\Http\Controllers\WebhookController;

Route::post('/github/deploy', [WebhookController::class, 'handle']);
```

4. Add webhook to your GitHub repo with content type `application/json` and a secret.

## Usage

Use the built-in Artisan commands to manage repositories:

```bash
php artisan github:add-repo
php artisan github:list-repos
php artisan github:remove-repo
```

## Database Structure

- `github_repositories`: Repository name and secret
- `github_branches`: Associated branches, with local clone path and environment
- `github_commands`: Commands to run on each branch upon deploy

## Example Commands

Laravel branch:
- `git pull origin main`
- `composer install --no-dev --optimize-autoloader`
- `php artisan config:cache`
- `php artisan migrate --force`

Next.js branch:
- `git pull origin main`
- `pnpm install --force`
- `pnpm run build`
- `pm2 reload <id>`

## Security

Each webhook is validated with a unique HMAC-SHA256 signature per repository.

## License

MIT