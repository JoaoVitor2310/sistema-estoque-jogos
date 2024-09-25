<?php

use App\Http\Controllers\TaxaController;
use App\Http\Controllers\VendaChaveTrocaController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Pages

Route::get('/fees', [TaxaController::class,'showMarketPlaceFees'])->name('fees'); // READ all fees

Route::get('/ranges-taxa-G2A', [TaxaController::class,'showRangesG2A'])->name('ranges-taxa-G2A');

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

// API

Route::prefix('games')->controller(VendaChaveTrocaController::class)->group(function () {
    Route::post('/', 'store')->name('games.store'); // CREATE
    Route::get('/', 'index')->name('games.index');  // READ all games
    Route::put('/{id}', 'update')->name('games.update'); // UPDATE
    Route::delete('/{id}', 'destroy')->name('games.destroy'); // DELETE
});

Route::prefix('fees')->controller(TaxaController::class)->group(function () {
    Route::post('/', 'store')->name('fees.store'); // CREATE
    Route::put('/{id}', 'update')->name('fees.update'); // UPDATE
    Route::delete('/{id}', 'destroy')->name('fees.destroy');
    Route::delete('/', 'destroyArray')->name('fees.destroyArray');
});