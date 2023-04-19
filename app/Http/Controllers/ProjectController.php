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
        Auth::user()->projects()->attach(Project::create($request->only('name')), ['user_rank' => 2]);
    }

    public function show(int $id)
    {
        $user = Auth::user();
        $project = $user->projects()->find($id);
        return new ProjectResource($project);
    }

    public function update(Request $request, string $id)
    {
        $user = Auth::user();
        $project = $user->projects()->find($id);
        if($project->pivot->user_rank == 2){
            $project->name = $request->name;
            $project->save();
        }
        else {
            return 'unauthorised';
        }
    }

    // this one doesn't work
    public function destroy(string $id)
    {
        $user = Auth::user();
        $project = $user->projects()->find($id);
        if($project->pivot->user_rank == 2){
            $project->delete();
        }
        else {
            return 'unauthorised';
        }
    }

    // Shared Projects

    public function invite(int $project_id, int $invited_user_id){
        $user = Auth::user();
        if ($this->has_user($project_id, $invited_user_id)){
            return 'user already in project';
        }
        else if ($this->has_user($project_id, $invited_user_id) == false){
            $project = $user->projects($project_id)->first();

            if($project->pivot->user_rank == 2){

                $invited_user = User::findOrFail($invited_user_id);
                $invited_user->projects()->attach($project, ['user_rank' => 1]);

                return 'user invited';
            }
            else {
                return 'unauthorised';
            }
        }
    }

    public function remove(int $project_id, int $removed_user_id){
        $user = Auth::user();

        if ($this->has_user($project_id, $removed_user_id)){
            $project = $user->projects($project_id)->first();
            $removed_user = User::findOrFail($removed_user_id);

            $remover_user_rank = $project->pivot->user_rank;
            $removed_user_rank = $removed_user->projects($project)->first()->pivot->user_rank;

            if ($remover_user_rank > $removed_user_rank){
                $removed_user->projects()->detach($project);
                return 'user removed';
            }
            else {
                return 'unauthorised';
            }
        }

        return 'user not found';
    }

    // custom functions
    
    private function has_user(int $project_id, int $user_id){
        $project = Project::findOrFail($project_id);

        $users = $project->users;
        foreach ($users as $user){
            if ($user->id == $user_id){
                return true;
            }
        }
        return false;
    }
}
