<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\ProjectResource;
use Illuminate\Http\Request;

use App\Models\User;

class ProjectController extends Controller
{

    // CRUD

    public function index()
    {

        $user = Auth::user();

        if ($user->token_name == "admin-token"){
            return ProjectResource::collection(Project::paginate());
        }

        $projects = $user->projects;

        return ProjectResource::collection($projects);
    }

    public function store(Request $request)
    {
        Auth::user()->projects()->attach(Project::create($request->only('name'), ['privilege' => 2]));
    }

    public function show(int $id)
    {
        $user = Auth::user();
        if ($user->token_name == "admin-token"){
            $project = Project::findOrFail($id);
            return ProjectResource::create($project);
        }
        $project = $user->projects()->find($id);
        return ProjectResource::create($project);
    }

    public function update(Request $request, string $id)
    {
        $user = Auth::user();
        if ($user->token_name == "admin-token"){
            $project = Project::findOrFail($id);
        }
        else {
            $project = $user->projects()->find($id);
        }
        $project->name = $request->name;
        $project->save();
    }

    public function destroy(string $id)
    {
        $user = Auth::user();
        if ($user->token_name == "admin-token"){
            $project = Project::findOrFail($id);
        }
        else {
            $project = $user->projects()->find($id);
        }
        $project->delete();
    }

    // Shared Projects

    public function invite(Project $project, User $invited_user){
        $user = Auth::user();
        $project = $user->projects($project)->first();
        // $invited_user = User::findOrFail($invited_user_id);
        $invited_user->projects()->attach($project, ['privilege' => 1]);
    }

    public function remove(Project $project, User $removed_user){
        $user = Auth::user();
        $project = $user->projects($project)->first();

        if ($user->$project->privilege == 2 && $removed_user->$project->privilege){
            $removed_user->projects()->detach($project);
        }
        else {
            return 'failed';
        }
    }
}
