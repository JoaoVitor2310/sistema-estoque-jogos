<?php

use App\Http\Controllers\TaxaController;
use App\Http\Controllers\VendaChaveTrocaController;
use App\Http\Controllers\ResourceController;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Socialite\Facades\Socialite;

// Pages

Route::get('/fees', [TaxaController::class,'showMarketPlaceFees'])->name('fees'); // READ all fees

Route::get('/ranges-taxa-G2A', [TaxaController::class,'showRangesG2A'])->name('ranges-taxa-G2A');

Route::get('/resources', [ResourceController::class,'show'])->name('resources');

Route::get('/venda-chave-troca', [VendaChaveTrocaController::class,'show'])->name('venda-chave-troca');

Route::get('/', function () {
    return Inertia::render('HomeView', []);
})->name('home');


Route::get('/login', function () {
    return Inertia::render('Login', [
        'props' => 'login',
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

Route::prefix('venda-chave-troca')->controller(VendaChaveTrocaController::class)->group(function () {
    Route::get('/paginated', 'paginated')->name('venda-chave-troca.paginated'); 
    Route::post('/search', 'search')->name('venda-chave-troca.search');
    Route::post('/', 'store')->name('venda-chave-troca.store'); 
    Route::put('/{id}', 'update')->name('venda-chave-troca.update'); 
    Route::delete('/{id}', 'destroy')->name('venda-chave-troca.destroy');
    Route::delete('/', 'destroyArray')->name('venda-chave-troca.destroyArray');
});

Route::get('/auth/redirect', function () {
    return Socialite::driver('google')->redirect();
});
 
Route::get('/auth/google/callback', function () {
    $googleUser = Socialite::driver('google')->user();
 
    $user = User::updateOrCreate([
        'google_id' => $googleUser->id,
    ], [
        'name' => $googleUser->name,
        'email' => $googleUser->email,
        'google_token' => $googleUser->token,
        'google_refresh_token' => $googleUser->refreshToken,
    ]);
 
    Auth::login($user);
 
    // return redirect('/venda-chave-troca');
    return redirect('/logged');
});

Route::get('/logged', function () {
    var_dump(auth()->user());
    var_dump(Auth::user());
    return;
});