<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ToDo extends Model
{
    use HasFactory;

    protected $fillable = [
        'description'
    ];

    public function projects(){
        return $this->morphMany(Project::class, 'containable');
    }
}
