<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DestinationController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\GalleryCategoryController;
use App\Http\Controllers\Admin\GalleryImageController;
use App\Http\Controllers\Admin\InvoiceController;
use App\Http\Controllers\Admin\PackageBuilderController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\TaxiRouteController;
use App\Http\Controllers\Admin\VehicleCapacityController;
use App\Http\Controllers\Admin\VehicleController;
use App\Http\Controllers\Admin\VehicleTypeController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\TaxiController;
use App\Models\Setting;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/taxi', [TaxiController::class, 'index'])->name('taxi');
Route::get('/packages', [PackageController::class, 'index'])->name('packages');
Route::get('/package-{slug}', [PackageController::class, 'show'])->name('package.show');
Route::get('/gallery', [GalleryController::class, 'index'])->name('gallery');
Route::get('/about', function () {
    $settings = Setting::current();
    return view('about', compact('settings'));
})->name('about');
Route::get('/contact', function () {
    $settings = Setting::current();
    return view('contact', compact('settings'));
})->name('contact');

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
        Route::get('faqs/data', [FaqController::class, 'data'])->name('faqs.data');
        Route::resource('faqs', FaqController::class)->except('show');
        Route::get('destinations/data', [DestinationController::class, 'data'])->name('destinations.data');
        Route::resource('destinations', DestinationController::class)->except('show');
        Route::post('gallery-categories/reorder', [GalleryCategoryController::class, 'reorder'])->name('gallery-categories.reorder');
        Route::resource('gallery-categories', GalleryCategoryController::class)->except(['show', 'create', 'edit'])->parameters(['gallery-categories' => 'galleryCategory']);
        Route::get('gallery-images/data', [GalleryImageController::class, 'data'])->name('gallery-images.data');
        Route::resource('gallery-images', GalleryImageController::class)->except('show')->parameters(['gallery-images' => 'galleryImage']);
        Route::get('custom-packages/data', [PackageBuilderController::class, 'data'])->name('custom-packages.data');
        Route::get('custom-packages/{customPackage}/pdf', [PackageBuilderController::class, 'downloadPdf'])->name('custom-packages.pdf');
        Route::resource('custom-packages', PackageBuilderController::class)->except('show')->parameters(['custom-packages' => 'customPackage']);
        Route::get('invoices/data', [InvoiceController::class, 'data'])->name('invoices.data');
        Route::get('invoices/{invoice}/pdf', [InvoiceController::class, 'downloadPdf'])->name('invoices.pdf');
        Route::resource('invoices', InvoiceController::class)->except('show');
    });
});
