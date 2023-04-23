<?php

namespace App\Http\Controllers;

use App\Http\Resources\ToDoResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\ToDo;

class ToDoController extends Controller
{
    public function index(int $project){
        $user = Auth::user();
        $project = $user->projects()->find($project);
        if ($project->pivot->user_rank >= 0){
            return ToDoResource::collection($project->todos);
        }
        return 'unauthorised';
    }

    public function show(int $project, int $todo_id){
        $user = Auth::user();
        $project = $user->projects()->find($project);
        if ($project->pivot->user_rank >= 0){
            $todo = $project->todos()->find($todo_id);
            if ($todo == null){
                return 'todo not found';
            }
            return new ToDoResource($todo);
        }
        return 'unauthorised';
    }

    public function store(Request $request, int $project){
        $user = Auth::user();
        $project = $user->projects()->find($project);
        if ($project->pivot->user_rank > 0){
            $todo = ToDo::create([
                'description' => $request->description,
                'project_id' => $project->id,
            ]);
            $project->todos->add($todo);
            return new ToDoResource($todo);
        }
        else {
            return 'unauthorised';
        }
    }
    public function update(Request $request, int $project, int $todo_id){
        $user = Auth::user();
        $project = $user->projects()->find($project);
        $todo = $project->todos()->find($todo_id);
        if ($todo == null){
            return 'todo not found';
        }

        if ($project->pivot->user_rank > 0){
            $todo->description = $request->description;
            $todo->save();
            return new ToDoResource($todo);
        }
        else {
            return 'unauthorised';
        }
    }

    public function delete(int $project, int $todo_id){
        $user = Auth::user();
        $project = $user->projects()->find($project);
        if ($project->pivot->user_rank > 0){
            $todo = $project->todos()->find($todo_id);
            if ($todo == null){
                return 'todo not found';
            }
            $todo->delete();
            return 'todo deleted';
        }
        return 'unauthorised';
    }
}
