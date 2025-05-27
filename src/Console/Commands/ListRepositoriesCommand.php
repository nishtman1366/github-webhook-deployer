<?php

namespace Nishtman\GithubWebhookDeployer\Console\Commands;

use Illuminate\Console\Command;
use Nishtman\GithubWebhookDeployer\Models\Repository;

class ListRepositoriesCommand extends Command
{
    protected $signature = 'repo:list';
    protected $description = 'List all registered repositories';

    public function handle(): int
    {
        $repos = Repository::all(['name', 'branch', 'path']);

        if ($repos->isEmpty()) {
            $this->info('No repositories found.');
            return self::SUCCESS;
        }

        $this->table(['Repository', 'Branch', 'Path'], $repos);
        return self::SUCCESS;
    }
}
