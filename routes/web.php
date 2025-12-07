<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\CheckinController;
use App\Http\Controllers\CheckoutController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {
    // Dashboard routes
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/report', [DashboardController::class, 'report'])->name('dashboard.report');
    Route::get('/dashboard/export-pdf', [DashboardController::class, 'exportPDF'])->name('dashboard.export-pdf');
    Route::get('/dashboard/export-excel', [DashboardController::class, 'exportExcel'])->name('dashboard.export-excel');

    // Driver routes
    Route::resource('drivers', DriverController::class);
    Route::get('/drivers/search', [DriverController::class, 'search'])->name('drivers.search');
    Route::get('/api/drivers/generate-id-card', [DriverController::class, 'generateIdCard'])->name('drivers.generate-id-card');

    // Room routes
    Route::resource('rooms', RoomController::class);
    Route::get('/api/rooms/available', [RoomController::class, 'available'])->name('rooms.available');

    // Check-in routes
    Route::resource('checkins', CheckinController::class);
    Route::post('/api/checkins/scan-card', [CheckinController::class, 'scanCard'])->name('checkins.scan-card');
    Route::get('/checkins/{checkin}/checkout-form', [CheckinController::class, 'getCheckoutForm'])->name('checkins.checkout-form');

    // Check-out routes
    Route::resource('checkouts', CheckoutController::class);
    Route::post('/checkouts/{checkout}/mark-paid', [CheckoutController::class, 'markAsPaid'])->name('checkouts.mark-paid');
    Route::get('/checkouts/reports', [CheckoutController::class, 'report'])->name('checkouts.report');
});

require __DIR__.'/auth.php';
