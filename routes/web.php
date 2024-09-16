<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('HomeView', []);
})->name('home');

Route::get('/venda-chave-troca', function () {
    return Inertia::render('VendaChaveTroca', [
        'name' => 'SECOND LINK',
    ]);
})->name('venda-chave-troca');

Route::get('/login', function () {
    return Inertia::render('VendaChaveTroca', [
        'name' => 'SECOND LINK',
    ]);
})->name('login');

Route::get('/taxas', function () {
    return Inertia::render('VendaChaveTroca', [
        'name' => 'SECOND LINK',
    ]);
})->name('taxas');

Route::get('/ranges-taxa-G2A', function () {
    return Inertia::render('VendaChaveTroca', [
        'name' => 'SECOND LINK',
    ]);
})->name('ranges-taxa-G2A');