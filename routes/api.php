<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TodoController;
use App\Http\Middleware\CheckTodoOwnership;

// Auth routes
Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login'])->name('login');

    // Protected routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
        Route::get('/profile', [AuthController::class, 'profile'])->name('profile');
    });
});

// Todo routes
Route::prefix('todos')->middleware('auth:sanctum')->group(function () {
    Route::post('/', [TodoController::class, 'store'])->name('todos.store');
    Route::get('/', [TodoController::class, 'index'])->name('todos.index');
    Route::middleware(CheckTodoOwnership::class)->group(function () {
        Route::get('/{todo}', [TodoController::class, 'show'])->name('todos.show');
        Route::put('/{todo}', [TodoController::class, 'update'])->name('todos.update');
        Route::delete('/{todo}', [TodoController::class, 'destroy'])->name('todos.destroy');
    });
});
