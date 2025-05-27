<?php

namespace Nishtman\GithubWebhookDeployer\Models;

use Illuminate\Database\Eloquent\Model;

class Repository extends Model
{
    protected $table = 'github_repositories';

    protected $fillable = ['name', 'secret'];

    public function branches()
    {
        return $this->hasMany(Branch::class);
    }
}
