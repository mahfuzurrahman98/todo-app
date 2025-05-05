<?php

use Illuminate\Support\Facades\Route;

// API routes will go here

// This catch-all route must be the last route defined
// It returns the main view for all routes to be handled by Vue Router
Route::get('/{any?}', function () {
    return view('welcome');
})->where('any', '.*');
