<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return Inertia::render('Home');
});


Route::get('/about', function () {
    return Inertia::render('About');
});


Route::get('/contact', function () {
    return Inertia::render('Contact'); // Contact.vue page
});
