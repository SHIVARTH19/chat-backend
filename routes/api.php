<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ThreadController;
use App\Http\Controllers\UserController;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);

// Protect routes with Sanctum middleware
// Route::middleware('auth:sanctum')->group(function () {
    Route::get('/messages', [ChatController::class, 'getMessages']);
    Route::post('/messages', [ChatController::class, 'sendMessage']);
    Route::get('/threads', [ThreadController::class, 'index']);
    Route::get('/users', [UserController::class, 'index']);
    Route::post('/threads', [ThreadController::class, 'store']);
    

// });
