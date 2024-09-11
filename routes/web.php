<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Users/Index', [
        'name' => 'João Vitor Gouveia',
    ]);
});
