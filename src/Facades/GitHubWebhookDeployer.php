<?php

namespace Nishtman\GitHubWebhookDeployer\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static static addRepo(string $name, string $secret)
 * @method static \Nishtman\GithubWebhookDeployer\Models\Repository|null getRepo(string $name = null)
 * @method static static addBranch(string $name, string $path, ?string $runner = null, ?string $env = null)
 * @method static static addCommand(string $branchName, string $key, string $command, int $order = 0)
 * @method static bool deleteRepo(string $name)
 * @method static bool deleteBranch(string $branchName)
 * @method static bool deleteCommand(string $branchName, string $key)
 *
 * @see \Nishtman\GithubWebhookDeployer\Services\GitHubDeployerService
 */
class GitHubWebhookDeployer extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'github-webhook-deployer';
    }
}