<?php

namespace Nishtman\GithubWebhookDeployer\Models;

use Illuminate\Database\Eloquent\Model;

class Command extends Model
{
    protected $table = 'github_commands';

    protected $fillable = ['branch_id', 'key', 'command', 'sort_order'];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}
