<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Anis marketing / onboarding landing page.
Route::get('/landing', function () {
    return view('landing');
})->name('landing');
