<?php
namespace Nishtman\GithubWebhookDeployer\Models;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    protected $table = 'github_branches';

    protected $fillable = ['repository_id', 'name', 'path', 'runner', 'env'];

    protected $casts = [
        'env' => 'array',
    ];

    public function repository()
    {
        return $this->belongsTo(Repository::class);
    }

    public function commands()
    {
        return $this->hasMany(Command::class)->orderBy('sort_order');
    }
}
