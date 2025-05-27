<?php

namespace Nishtman\GithubWebhookDeployer\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Nishtman\GithubWebhookDeployer\Models\Repository;

class WebhookController extends Controller
{
    public function handle(Request $request)
    {
        $payload = $request->getContent();


        // 2. تبدیل payload به آرایه
        $data = json_decode($payload, true);

        // 3. استخراج نام ریپو و برنچ
        $repoName = $data['repository']['full_name'] ?? null;
        $ref = $data['ref'] ?? null; // refs/heads/main
        $branchName = str_replace('refs/heads/', '', $ref);

        if (!$repoName || !$branchName) {
            Log::error('[GitHub Deployer] Missing repo or branch info');
            return response('Missing data', 400);
        }

        // 4. دریافت ریپو از دیتابیس
        $repository = Repository::where('name', $repoName)->first();
        if (!$repository) {
            Log::warning("[GitHub Deployer] Repo not registered: {$repoName}");
            return response('Repository not found', 404);
        }

        // 1. اعتبارسنجی
        $signature = 'sha256=' . hash_hmac('sha256', $payload, $repository->secret);
        $sentSignature = $request->header('X-Hub-Signature-256');

        if (!hash_equals($signature, $sentSignature)) {
            Log::warning('[GitHub Deployer] Invalid signature');
            return response('Invalid signature', 403);
        }

        $branch = $repository->branches()->where('name', $branchName)->first();
        if (!$branch) {
            Log::warning("[GitHub Deployer] Branch not registered: {$branchName} on {$repoName}");
            return response('Branch not found', 404);
        }

        // 5. اجرای دستورات
        foreach ($branch->commands as $command) {
            try {
                Log::info("[GitHub Deployer] Running {$command->key} for {$repoName}: {$command->command}");

                $process = Process::fromShellCommandline($command->command, $branch->path, $branch->env ?? null);
                $process->setTimeout(300); // 5 دقیقه
                $process->run();

                if (!$process->isSuccessful()) {
                    throw new ProcessFailedException($process);
                }

                Log::info("[GitHub Deployer] ✅ {$command->key} succeeded");
            } catch (\Throwable $e) {
                Log::error("[GitHub Deployer] ❌ Command failed: {$command->key}", [
                    'error' => $e->getMessage(),
                ]);
                return response("Command {$command->key} failed", 500);
            }
        }

        return response('Deployment complete', 200);
    }
}
