<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\VideoController;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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


Route::controller(PostController::class)->prefix('post')->group(function(){
    Route::get('list/{id}','list');
    Route::post('create','create');
    Route::post('comment','comment');
    Route::post('update','update');
    Route::get('delete/{id}','delete');
});

Route::controller(VideoController::class)->prefix('video')->group(function(){
    Route::get('list/{id}','list');
    Route::post('create','create');
    Route::post('comment','comment');
    Route::post('update','update');
    Route::get('delete/{id}','delete');
});

Route::controller(CommentController::class)->prefix('comment')->group(function(){
    Route::get('list/{id}','list');
    Route::get('update/{id}','update');
    Route::get('delete/{id}','delete');
});
