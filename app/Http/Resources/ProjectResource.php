<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        $members = $this->users;
        $adminList = [];
        $memberList = [];

        foreach ($members as $member){
            if ($member->projects($this)->first()->pivot->user_rank == 2){
                $member = new UserResource($member);
                array_push($adminList, $member);
            }
            else if ($member->projects($this)->first()->pivot->user_rank == 1){
                $member = new UserResource($member);
                array_push($memberList, $member);
            }
        }

        $todos = $this->todos;
        $todoList = [];

        foreach($todos as $todo){
            $todo = new ToDoResource($todo);
            array_push($todoList, $todo);
        }

        return ([
            'id' => $this->id,
            'name' => $this->name,
            'memberCount' => count($this->users),
            'adminList' => $adminList,
            'memberList' => $memberList,
            'todoList' => $todoList,
        ]);
    }
}
