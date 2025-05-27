<?php

namespace Nishtman\GithubWebhookDeployer\Http\Controllers;

use Illuminate\Http\Request;
use Nishtman\GithubWebhookDeployer\Models\Repository;
use App\Http\Controllers\Controller;

class RepositoryController extends Controller
{
    public function index()
    {
        return Repository::all();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'full_name' => 'required|string|unique:repositories,full_name',
            'branches' => 'required|array',
        ]);

        $repo = Repository::create($data);

        return response()->json($repo, 201);
    }

    public function update(Request $request, Repository $repository)
    {
        $data = $request->validate([
            'branches' => 'required|array',
        ]);

        $repository->update($data);

        return response()->json($repository);
    }

    public function destroy(Repository $repository)
    {
        $repository->delete();

        return response()->json(null, 204);
    }
}
