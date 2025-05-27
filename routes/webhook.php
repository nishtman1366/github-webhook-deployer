<?php

use Illuminate\Support\Facades\Route;
use Nishtman\GithubWebhookDeployer\Http\Controllers\WebhookController;
use Nishtman\GithubWebhookDeployer\Http\Controllers\RepositoryController;

Route::post('/github-webhook', [WebhookController::class, 'handle'])->name('github-webhook');

Route::middleware('api')->group(function () {
    Route::apiResource('github-webhook-repositories', RepositoryController::class);
});
