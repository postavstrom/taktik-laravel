<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\TagController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::resource('posts', PostController::class);

Route::middleware('throttle:api')->group(function () {
    Route::resource('users', UserController::class);
    Route::resource('posts', PostController::class);
    Route::resource('comments', CommentController::class);
    Route::resource('tags', TagController::class);
});
