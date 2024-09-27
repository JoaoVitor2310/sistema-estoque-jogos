<?php

use App\Http\Controllers\TaxaController;
use App\Http\Controllers\VendaChaveTrocaController;
use App\Http\Controllers\ResourceController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Pages

Route::get('/fees', [TaxaController::class,'showMarketPlaceFees'])->name('fees'); // READ all fees

Route::get('/ranges-taxa-G2A', [TaxaController::class,'showRangesG2A'])->name('ranges-taxa-G2A');

Route::get('/resources', [ResourceController::class,'show'])->name('resources');


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
    Route::post('/', 'store')->name('fees.store');
    Route::put('/{id}', 'update')->name('fees.update'); 
    Route::delete('/{id}', 'destroy')->name('fees.destroy');
    Route::delete('/', 'destroyArray')->name('fees.destroyArray');
});

Route::prefix('ranges-g2a')->controller(TaxaController::class)->group(function () {
    Route::post('/', 'storeRangeG2A')->name('ranges-g2a.storeRangeG2A'); 
    Route::put('/{id}', 'updateRangeG2A')->name('ranges-g2a.updateRangeG2A'); 
    Route::delete('/{id}', 'destroyRangeG2A')->name('ranges-g2a.destroyRangeG2A');
    Route::delete('/', 'destroyArrayG2A')->name('ranges-g2a.destroyArrayG2A');
});

Route::prefix('resources')->controller(ResourceController::class)->group(function () {
    Route::post('/', 'store')->name('resources.store'); 
    Route::put('/{id}', 'update')->name('resources.update'); 
    Route::delete('/{id}', 'destroy')->name('resources.destroy');
    Route::delete('/', 'destroyArray')->name('resources.destroyArray');
});