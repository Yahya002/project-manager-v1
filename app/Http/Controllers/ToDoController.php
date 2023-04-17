<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use App\Models\Project;
use App\Models\ToDo;

class ToDoController extends Controller
{

    public function store(Request $request, int $project){
        $user = Auth::user();
        $project = $user->projects($project)->first();
        if ($project->pivot->user_rank > 0){
            $todo = ToDo::create([
                'description' => $request->description,
                'project_id' => $project->id,
            ]);
            $project->todos->add($todo);
        }
        else {
            return 'unauthorised';
        }
    }
    public function update(Request $request, int $project, int $todo){
        $user = Auth::user();
        $project = $user->projects($project)->first();
        $todo = $project->todos($todo);

        if ($project->pivot->user_rank > 0){
            $todo->description = $request->description;
            $todo->save();
        }
        else {
            return 'unauthorised';
        }
    }
}
