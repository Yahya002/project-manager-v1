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
        $projects = $user->projects;
        return ProjectResource::collection($projects);
    }

    public function store(Request $request)
    {
        Auth::user()->projects()->attach(Project::create($request->only('name')), ['privilege' => 2]);
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
        $project = $user->projects()->find($id);
        if($project->pivot->privilege == 2){
            $project->name = $request->name;
            $project->save();
        }
        else {
            return 'unauthorised';
        }
    }

    public function destroy(string $id)
    {
        $user = Auth::user();
        $project = $user->projects()->find($id);
        if($project->pivot->privilege == 2){
            $project->delete();
        }
        else {
            return 'unauthorised';
        }
    }

    // Shared Projects

    public function invite(int $project, int $invited_user_id){
        $user = Auth::user();
        $project = $user->projects($project)->first();

        if($project->pivot->privilege == 2){
            $invited_user = User::findOrFail($invited_user_id);
            $invited_user->projects()->attach($project, ['privilege' => 1]);
        }
        else {
            return 'unauthorised';
        }
    }

    public function remove(int $project, int $removed_user_id){
        $user = Auth::user();
        $project = $user->projects($project)->first();
        $removed_user = User::findOrFail($removed_user_id);

        $removerPrivilege = $project->pivot->privilege;
        $removedPrivilege = $removed_user->projects($project)->first()->pivot->privilege;

        if ($removerPrivilege >= $removedPrivilege){
            $removed_user->projects()->detach($project);
        }
        else {
            return 'unauthorised';
        }
    }
}
