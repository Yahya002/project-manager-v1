<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ToDoController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Route::get('/admin', [UserController::class, 'admin']);

Route::group([
    'prefix' => '/v1/projects',
    'middleware' => 'auth:sanctum'
], function(){

    Route::get('/', [ProjectController::class, 'index']);
    Route::post('/create', [ProjectController::class, 'store']);
    Route::get('/{id}', [ProjectController::class, 'show']);
    Route::patch('/{id}', [ProjectController::class, 'update']);
    Route::delete('/{id}', [ProjectController::class, 'destroy']);

    Route::get('/{project}/invite/{user}', [ProjectController::class, 'invite']);
    Route::get('/{project}/remove/{user}', [ProjectController::class, 'remove']);

    Route::post('/{project}/todos/create', [ToDoController::class, 'store']);
    // Route::get('/logout', [UserController::class, 'logout']);
});

Route::group([
    'middleware' => 'guest',
    'prefix' => '/v1',
], function(){
    Route::post('/register', [UserController::class, 'register']);
    Route::post('/login', [UserController::class, 'login']);
});


