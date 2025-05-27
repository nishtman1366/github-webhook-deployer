<?php

namespace Nishtman\GithubWebhookDeployer\Console\Commands;

use Illuminate\Console\Command;
use Nishtman\GithubWebhookDeployer\Models\Repository;

class RemoveRepositoryCommand extends Command
{
    protected $signature = 'repo:remove
                            {name : Full repo name}
                            {branch : Branch name}';

    protected $description = 'Remove a repository from the system';

    public function handle(): int
    {
        $deleted = Repository::where('name', $this->argument('name'))
            ->where('branch', $this->argument('branch'))
            ->delete();

        if ($deleted) {
            $this->info('Repository removed.');
        } else {
            $this->warn('No matching repository found.');
        }

        return self::SUCCESS;
    }
}
