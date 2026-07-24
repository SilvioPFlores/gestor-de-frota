<?php

use App\Http\Controllers\{ProfileController,UserController,TripController,VehicleController,DriverController};
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/limpar-tudo', function() {
    Artisan::call('optimize:clear');
    return "Cache limpo com sucesso!";
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    // Rotas de Veiculos
    Route::get('/veiculos', [VehicleController::class, 'index'])->name('vehicles.index');
    Route::post('/veiculos', [VehicleController::class, 'store'])->name('vehicles.store');
    Route::put('/veiculos/{vehicle}', [VehicleController::class, 'update'])->name('vehicles.update');
    Route::delete('/veiculos/{vehicle}', [VehicleController::class, 'destroy'])->name('vehicles.destroy');

    // Rotas de Motoristas
    Route::get('/motoristas', [DriverController::class, 'index'])->name('drivers.index');
    Route::post('/motoristas', [DriverController::class, 'store'])->name('drivers.store');
    Route::put('/motoristas/{driver}', [DriverController::class, 'update'])->name('drivers.update');
    Route::delete('/motoristas/{driver}', [DriverController::class, 'destroy'])->name('drivers.destroy');

    // Rotas de Motoristas
    Route::get('/viagens', [TripController::class, 'index'])->name('trips.index');
    Route::post('/viagens', [TripController::class, 'store'])->name('trips.store');
    Route::put('/viagens/{trip}', [TripController::class, 'update'])->name('trips.update');
    Route::delete('/viagens/{trip}', [TripController::class, 'destroy'])->name('trips.destroy');

});
   
Route::middleware(['auth', 'can:gerenciar usuarios'])->group(function () {
    Route::get('/usuarios', [UserController::class, 'index'])->name('users.index');
    Route::put('/usuarios/{user}', [UserController::class, 'update'])->name('users.update');
});

require __DIR__.'/auth.php';
