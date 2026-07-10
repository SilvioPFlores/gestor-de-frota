<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{ProfileController,UserController,VehicleController};

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
 
    Route::get('/veiculos', [VehicleController::class, 'index'])->name('vehicles.index');
    Route::post('/veiculos', [VehicleController::class, 'store'])->name('vehicles.store');
    Route::put('/veiculos/{vehicle}', [VehicleController::class, 'update'])->name('vehicles.update');
    Route::delete('/veiculos/{vehicle}', [VehicleController::class, 'destroy'])->name('vehicles.destroy');

});
   
Route::middleware(['auth', 'can:gerenciar usuarios'])->group(function () {
    Route::get('/usuarios', [UserController::class, 'index'])->name('users.index');
    Route::put('/usuarios/{user}', [UserController::class, 'update'])->name('users.update');
});

require __DIR__.'/auth.php';
