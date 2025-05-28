<?php

namespace Nishtman\GithubWebhookDeployer\Services;

use Nishtman\GithubWebhookDeployer\Models\Repository;

class GitHubDeployerService
{
    protected ?Repository $repo = null;

    public function addRepo(string $name, string $secret): static
    {
        $this->repo = Repository::updateOrCreate(['name' => $name], ['secret' => $secret]);
        return $this;
    }

    public function getRepo(string $name = null): ?Repository
    {
        if ($name) {
            $this->repo = Repository::where('name', $name)->first();
        }

        return $this->repo;
    }

    public function addBranch(string $name, string $path, ?string $runner = null, ?string $env = null): static
    {
        if (!$this->repo) {
            throw new \Exception("No repository selected");
        }

        $this->repo->branches()->updateOrCreate(
            ['name' => $name],
            ['path' => $path, 'runner' => $runner, 'env' => $env]
        );

        return $this;
    }

    public function addCommand(string $branchName, string $key, string $command, int $order = 0): static
    {
        if (!$this->repo) {
            throw new \Exception("No repository selected");
        }

        $branch = $this->repo->branches()->where('name', $branchName)->first();

        if (!$branch) {
            throw new \Exception("Branch {$branchName} not found");
        }

        $branch->commands()->updateOrCreate(
            ['key' => $key],
            ['command' => $command, 'sort_order' => $order]
        );

        return $this;
    }
}