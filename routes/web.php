<?php

use App\Http\Controllers\TaxaController;
use App\Http\Controllers\VendaChaveTrocaController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/fees', [TaxaController::class,'showMarketPlaceFees'])->name('fees'); // READ all fees

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

// Route::get('/taxas', function () {
//     return Inertia::render('VendaChaveTroca', [
//         'name' => 'SECOND LINK',
//     ]);
// })->name('taxas');

Route::get('/ranges-taxa-G2A', function () {
    return Inertia::render('VendaChaveTroca', [
        'name' => 'SECOND LINK',
    ]);
})->name('ranges-taxa-G2A');




Route::get('/games', [VendaChaveTrocaController::class,'index'])->name('index'); // READ all games
Route::post('/games', [VendaChaveTrocaController::class,'store'])->name('store'); // CREATE
Route::put('/games/{id}', [VendaChaveTrocaController::class,'update'])->name('update'); // UPDATE
Route::delete('/games/{id}', [VendaChaveTrocaController::class,'destroy'])->name('destroy'); // DELETE


