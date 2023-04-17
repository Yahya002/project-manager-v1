<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ToDo extends Model
{
    use HasFactory;

    protected $fillable = [
        'description',
        'project_id',
    ];

    public function projects(){
        return $this->belongsTo(Project::class);
    }
}
