<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TodoController;
use App\Http\Middleware\CheckTodoOwnership;

// Auth routes
Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login'])->name('auth.login');

    // Protected routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');
        Route::get('/profile', [AuthController::class, 'profile'])->name('auth.profile');
    });
});

// Todo routes
Route::prefix('todos')->middleware('auth:sanctum')->group(function () {
    Route::post('/', [TodoController::class, 'store'])->name('todos.store');
    Route::get('/', [TodoController::class, 'index'])->name('todos.index');
    Route::middleware(CheckTodoOwnership::class)->group(function () {
        Route::get('/{id}', [TodoController::class, 'show'])->name('todos.show');
        Route::put('/{id}', [TodoController::class, 'update'])->name('todos.update');
        Route::delete('/{id}', [TodoController::class, 'destroy'])->name('todos.destroy');
    });
});
