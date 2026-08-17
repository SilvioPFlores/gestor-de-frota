<?php

use App\Http\Controllers\{ProfileController, UserController, TripController, VehicleController, DriverController};
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

Route::get('/limpar-tudo', function () {
    Artisan::call('optimize:clear');
    return "Cache limpo com sucesso!";
});

/*Route::get('/', function () {
    //return view('welcome');
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');*/

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Veículos
    Route::get('/veiculos', [VehicleController::class, 'index'])
        ->middleware('can:veiculos.visualizar')
        ->name('vehicles.index');

    Route::post('/veiculos', [VehicleController::class, 'store'])
        ->middleware('can:veiculos.criar')
        ->name('vehicles.store');

    Route::put('/veiculos/{vehicle}', [VehicleController::class, 'update'])
        ->middleware('can:veiculos.editar')
        ->name('vehicles.update');

    Route::delete('/veiculos/{vehicle}', [VehicleController::class, 'destroy'])
        ->middleware('can:veiculos.excluir')
        ->name('vehicles.destroy');

    // Motoristas
    Route::get('/motoristas', [DriverController::class, 'index'])
        ->middleware('can:motoristas.visualizar')
        ->name('drivers.index');

    Route::post('/motoristas', [DriverController::class, 'store'])
        ->middleware('can:motoristas.criar')
        ->name('drivers.store');

    Route::put('/motoristas/{driver}', [DriverController::class, 'update'])
        ->middleware('can:motoristas.editar')
        ->name('drivers.update');

    Route::delete('/motoristas/{driver}', [DriverController::class, 'destroy'])
        ->middleware('can:motoristas.excluir')
        ->name('drivers.destroy');

    // Viagens
    Route::get('/viagens', [TripController::class, 'index'])
        ->middleware('can:viagens.visualizar')
        ->name('trips.index');

    Route::post('/viagens', [TripController::class, 'store'])
        ->middleware('can:viagens.criar')
        ->name('trips.store');

    Route::put('/viagens/{trip}', [TripController::class, 'update'])
        ->middleware('can:update,trip')
        ->name('trips.update');

    Route::patch('/viagens/{trip}/motorista', [TripController::class, 'updateDriver'])
        ->middleware('can:updateDriver,trip')
        ->name('trips.update-driver');

    Route::patch('/viagens/{trip}/veiculo', [TripController::class, 'updateVehicle'])
        ->middleware('can:updateVehicle,trip')
        ->name('trips.update-vehicle');

    Route::patch('/viagens/{trip}/status', [TripController::class, 'updateStatus'])
        ->middleware('can:updateStatus,trip')
        ->name('trips.update-status');

    Route::patch('/viagens/{trip}/cancelar', [TripController::class, 'cancel'])
        ->middleware('can:delete,trip')
        ->name('trips.cancel');
});

Route::middleware(['auth', 'verified', 'can:usuarios.visualizar'])->group(function () {
    Route::get('/usuarios', [UserController::class, 'index'])
        ->name('users.index');
});

Route::middleware(['auth', 'verified', 'can:usuarios.alterar_nivel'])->group(function () {
    Route::put('/usuarios/{user}', [UserController::class, 'update'])
        ->name('users.update');
});

require __DIR__ . '/auth.php';
