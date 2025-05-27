<?php

namespace Nishtman\GithubWebhookDeployer\Providers;

use Illuminate\Support\ServiceProvider;
use Nishtman\GithubWebhookDeployer\Console\Commands\AddRepositoryCommand;
use Nishtman\GithubWebhookDeployer\Console\Commands\ListRepositoriesCommand;
use Nishtman\GithubWebhookDeployer\Console\Commands\RemoveRepositoryCommand;

class WebhookDeployerServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // بارگذاری مایگریشن‌ها از مسیر صحیح
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');

        // انتشار مایگریشن‌ها و کانفیگ
        $this->publishes([
            __DIR__ . '/../../database/migrations' => database_path('migrations'),
        ], 'webhook-deployer-migrations');

        $this->publishes([
            __DIR__ . '/../../config/webhook-deployer.php' => config_path('webhook-deployer.php'),
        ], 'webhook-deployer-config');

        // بارگذاری روت‌ها از فایل webhook.php که داخل مسیر routes قرار داره
        $this->loadRoutesFrom(__DIR__ . '/../../routes/webhook.php');
    }

    public function register()
    {
        // رجیستر کردن دستورات کنسول پکیج
        $this->commands([
            AddRepositoryCommand::class,
            ListRepositoriesCommand::class,
            RemoveRepositoryCommand::class,
        ]);
    }
}
