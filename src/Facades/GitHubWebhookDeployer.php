<?php

namespace Nishtman\GitHubWebhookDeployer\Facades;

use Illuminate\Support\Facades\Facade;

class GitHubWebhookDeployer extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'github-webhook-deployer';
    }
}