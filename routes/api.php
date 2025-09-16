<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Auth
Route::post('/register', [AuthController::class, 'register']);
Route::get('/users', [AuthController::class, 'getAllUsers']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

// CRUD Task
Route::post('/create', [TaskController::class, 'create'])->middleware('auth:sanctum');
Route::get('/tasks', [TaskController::class, 'index']);
Route::put('/update/{id}', [TaskController::class, 'update'])->middleware('auth:sanctum');
Route::delete('/delete/{id}', [TaskController::class, 'delete'])->middleware('auth:sanctum');


