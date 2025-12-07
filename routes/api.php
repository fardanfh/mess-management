<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\NFCController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// NFC API Routes (Simulasi pembacaan NFC)
Route::prefix('nfc')->group(function () {
    Route::get('/read/{id_card}', [NFCController::class, 'read'])->name('nfc.read');
    Route::post('/checkin', [NFCController::class, 'checkin'])->name('nfc.checkin');
    Route::post('/checkout', [NFCController::class, 'checkout'])->name('nfc.checkout');
});
