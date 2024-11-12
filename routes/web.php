<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;


Route::get('/', function () {
    return Inertia::render('Home');
});


Route::get('/task', function () {
    return Inertia::render('Task');
});


Route::get('/contact', function () {
    return Inertia::render('Contact'); // Contact.vue page
});


Route::resource('tasks', TaskController::class);
