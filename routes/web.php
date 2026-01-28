<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\LockerController;
use App\Http\Controllers\CheckinController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\FineController;
use App\Http\Controllers\DriverReportController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;

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
    // Dashboard routes - accessible to all authenticated users
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::middleware('permission:view_dashboard')->group(function () {
        Route::get('/dashboard/report', [DashboardController::class, 'report'])->name('dashboard.report');
        Route::get('/dashboard/export-pdf', [DashboardController::class, 'exportPDF'])->name('dashboard.export-pdf');
        Route::get('/dashboard/export-excel', [DashboardController::class, 'exportExcel'])->name('dashboard.export-excel');
    });

    // Driver routes - require manage_drivers permission
    Route::middleware('permission:manage_drivers')->group(function () {
        Route::resource('drivers', DriverController::class);
        Route::get('/drivers/search', [DriverController::class, 'search'])->name('drivers.search');
        Route::get('/api/drivers/generate-id-card', [DriverController::class, 'generateIdCard'])->name('drivers.generate-id-card');
    });

    // Room routes - require manage_rooms permission
    Route::middleware('permission:manage_rooms')->group(function () {
        Route::resource('rooms', RoomController::class);
        Route::get('/api/rooms/available', [RoomController::class, 'available'])->name('rooms.available');
    });

    // Locker routes - require manage_rooms permission
    Route::middleware('permission:manage_rooms')->group(function () {
        Route::resource('lockers', LockerController::class);
        Route::get('/api/lockers/available', [LockerController::class, 'available'])->name('lockers.available');
    });

    // Check-in routes - require process_checkin permission
    Route::middleware('permission:process_checkin')->group(function () {
        Route::resource('checkins', CheckinController::class);
        Route::post('/api/checkins/scan-card', [CheckinController::class, 'scanCard'])->name('checkins.scan-card');
        Route::get('/api/checkins/available-lockers', [CheckinController::class, 'getAvailableLockers'])->name('checkins.available-lockers');
        Route::get('/checkins/{checkin}/checkout-form', [CheckinController::class, 'getCheckoutForm'])->name('checkins.checkout-form');
        Route::get('/checkins/{checkin}/add-fine', [CheckinController::class, 'addFineForm'])->name('checkins.add-fine');
    });

    // Check-out routes - require process_checkout permission
    Route::middleware('permission:process_checkout')->group(function () {
        Route::resource('checkouts', CheckoutController::class);
        Route::post('/checkouts/{checkout}/mark-paid', [CheckoutController::class, 'markAsPaid'])->name('checkouts.mark-paid');
        Route::get('/checkouts/reports', [CheckoutController::class, 'report'])->name('checkouts.report');
    });

    // Fine routes - require process_checkout permission
    Route::middleware('permission:process_checkout')->group(function () {
        Route::post('/fines', [FineController::class, 'store'])->name('fines.store');
        Route::delete('/fines/{fine}', [FineController::class, 'destroy'])->name('fines.destroy');
        Route::patch('/fines/{fine}/restore', [FineController::class, 'restore'])->name('fines.restore');
    });

    // Driver Report routes - require view_reports permission
    Route::middleware('permission:view_reports')->group(function () {
        Route::get('/reports/driver-report', [DriverReportController::class, 'index'])->name('driver-report.index');
        Route::get('/reports/driver-report/export', [DriverReportController::class, 'exportExcel'])->name('driver-report.export');
    });

    // Management - Role and Permission management (management only)
    Route::prefix('management')->name('management.')->middleware('permission:manage_roles')->group(function () {
        Route::resource('roles', RoleController::class);
        Route::resource('permissions', PermissionController::class);
    });
});


require __DIR__.'/auth.php';
