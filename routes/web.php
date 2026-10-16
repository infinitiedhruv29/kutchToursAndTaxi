<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\TaxiRouteController;
use App\Http\Controllers\Admin\VehicleController;
use App\Http\Controllers\Admin\VehicleTypeController;
use App\Http\Controllers\Admin\VehicleCapacityController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('login', [AuthController::class, 'create'])->name('login');
        Route::post('login', [AuthController::class, 'store'])->name('login.store');
    });

    Route::middleware(['auth', 'auth.session'])->group(function () {
        Route::get('/', DashboardController::class)->name('dashboard');
        Route::post('logout', [AuthController::class, 'destroy'])->name('logout');
        Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::put('profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
        Route::get('settings', [SettingsController::class, 'edit'])->name('settings.edit');
        Route::put('settings', [SettingsController::class, 'update'])->name('settings.update');
        Route::get('routes/data', [TaxiRouteController::class, 'data'])->name('routes.data');
        Route::resource('routes', TaxiRouteController::class)->except('show')->parameters(['routes' => 'taxiRoute']);
        Route::get('vehicles/data', [VehicleController::class, 'data'])->name('vehicles.data');
        Route::resource('vehicles', VehicleController::class)->except('show');
        Route::get('vehicle-types/data', [VehicleTypeController::class, 'data'])->name('vehicle-types.data');
        Route::resource('vehicle-types', VehicleTypeController::class)->except('show')->parameters(['vehicle-types' => 'vehicleType']);
        Route::get('vehicle-capacities/data', [VehicleCapacityController::class, 'data'])->name('vehicle-capacities.data');
        Route::resource('vehicle-capacities', VehicleCapacityController::class)->except('show')->parameters(['vehicle-capacities' => 'vehicleCapacity']);
    });
});
