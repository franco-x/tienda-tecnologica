<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MarcaController;
use App\Http\Controllers\DispositivoController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dispositivos.index');
});

Route::get('/dashboard', function () {
    return redirect()->route('dispositivos.index');
})->name('dashboard');

Route::resource('marcas', MarcaController::class);
Route::resource('dispositivos', DispositivoController::class);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';