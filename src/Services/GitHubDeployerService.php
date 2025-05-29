<?php

namespace Nishtman\GithubWebhookDeployer\Services;

use Nishtman\GithubWebhookDeployer\Models\Repository;
use Nishtman\GithubWebhookDeployer\Models\Branch;
use Nishtman\GithubWebhookDeployer\Models\Command;

class GitHubDeployerService
{
    /**
     * The currently selected repository.
     */
    protected ?Repository $repo = null;

    /**
     * Create or update a repository.
     *
     * @param string $name
     * @param string $secret
     * @return static
     */
    public function addRepo(string $name, string $secret): static
    {
        $this->repo = Repository::updateOrCreate(['name' => $name], ['secret' => $secret]);
        return $this;
    }

    /**
     * Get a repository by name, or return the current repository.
     *
     * @param string|null $name
     * @return Repository|null
     */
    public function getRepo(string $name = null): ?Repository
    {
        if ($name) {
            $this->repo = Repository::where('name', $name)->first();
        }

        return $this->repo;
    }

    /**
     * Create or update a branch in the current repository.
     *
     * @param string $name
     * @param string $path
     * @param string|null $runner
     * @param string|null $env
     * @return static
     * @throws \Exception
     */
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

    /**
     * Create or update a command for a given branch.
     *
     * @param string $branchName
     * @param string $key
     * @param string $command
     * @param int $order
     * @return static
     * @throws \Exception
     */
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

    /**
     * Delete a repository by name with all its branches and commands.
     *
     * @param string $name
     * @return bool
     */
    public function deleteRepo(string $name): bool
    {
        $repo = Repository::where('name', $name)->first();

        if (!$repo) {
            return false;
        }

        foreach ($repo->branches as $branch) {
            $branch->commands()->delete();
        }

        $repo->branches()->delete();
        return $repo->delete();
    }

    /**
     * Delete a branch by name from the current repository.
     *
     * @param string $branchName
     * @return bool
     * @throws \Exception
     */
    public function deleteBranch(string $branchName): bool
    {
        if (!$this->repo) {
            throw new \Exception("No repository selected");
        }

        $branch = $this->repo->branches()->where('name', $branchName)->first();

        if (!$branch) {
            return false;
        }

        $branch->commands()->delete();
        return $branch->delete();
    }

    /**
     * Delete a command by key from a specific branch in the current repository.
     *
     * @param string $branchName
     * @param string $key
     * @return bool
     * @throws \Exception
     */
    public function deleteCommand(string $branchName, string $key): bool
    {
        if (!$this->repo) {
            throw new \Exception("No repository selected");
        }

        $branch = $this->repo->branches()->where('name', $branchName)->first();

        if (!$branch) {
            return false;
        }

        return $branch->commands()->where('key', $key)->delete();
    }
}
