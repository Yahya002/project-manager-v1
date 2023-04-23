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

Route::group([
    'prefix' => '/v1/projects',
    'middleware' => 'auth:sanctum'
], function(){

    Route::get('/', [ProjectController::class, 'index']);
    Route::get('/{project}', [ProjectController::class, 'show']);
    Route::post('/', [ProjectController::class, 'store']);
    Route::patch('/{project}', [ProjectController::class, 'update']);
    Route::delete('/{project}', [ProjectController::class, 'destroy']);

    Route::get('/{project}/invite/{user}', [ProjectController::class, 'invite']);
    Route::get('/{project}/remove/{user}', [ProjectController::class, 'remove']);

    Route::group([
        'prefix' => '/{project}/todos'
    ],function (){
        Route::get('/', [ToDoController::class, 'index']);
        Route::get('/{todo}', [ToDoController::class, 'show']);
        Route::post('/', [ToDoController::class, 'store']);
        Route::patch('/{todo}', [ToDoController::class, 'update']);
        Route::delete('/{todo}', [ToDoController::class, 'index']);
    });
});

Route::group([
    'middleware' => 'guest',
    'prefix' => '/v1',
], function(){
    Route::post('/register', [UserController::class, 'register']);
    Route::post('/login', [UserController::class, 'login']);
});
