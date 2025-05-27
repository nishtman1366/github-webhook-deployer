<?php

namespace Nishtman\GithubWebhookDeployer\Console\Commands;

use Illuminate\Console\Command;
use Nishtman\GithubWebhookDeployer\Models\Repository;

class AddRepositoryCommand extends Command
{
    protected $signature = 'repo:add
                            {name : Full repo name (e.g. user/project)}
                            {branch : Branch name (e.g. main or dev)}
                            {path : Local path on server}
                            {secret : GitHub webhook secret}
                            {--commands= : JSON string of commands to run}';

    protected $description = 'Add a new repository to the deployment system';

    public function handle(): int
    {
        $commands = json_decode($this->option('commands') ?? '{}', true);

        if (!is_array($commands)) {
            $this->error('Invalid JSON format for --commands option.');
            return self::FAILURE;
        }

        Repository::updateOrCreate(
            ['name' => $this->argument('name'), 'branch' => $this->argument('branch')],
            [
                'path' => $this->argument('path'),
                'secret' => $this->argument('secret'),
                'commands' => $commands
            ]
        );

        $this->info('Repository saved successfully.');
        return self::SUCCESS;
    }
}
