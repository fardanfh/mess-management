<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\NFCController;
use Illuminate\Support\Facades\Log;

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

// Violations API Routes
Route::get('/violations/{driver_id}', function (Request $request, $driver_id) {
    try {
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');
        
        if (!$startDate || !$endDate) {
            return response()->json([
                'violations' => [],
                'error' => 'Missing start_date or end_date parameter'
            ], 400);
        }
        
        // Get all checkins for the driver in the period
        $checkins = \App\Models\Checkin::where('driver_id', $driver_id)
            ->whereDate('check_out_time', '>=', $startDate)
            ->whereDate('check_out_time', '<=', $endDate)
            ->with('fines')
            ->get();
        
        // Collect all violations from those checkins
        $violations = [];
        foreach ($checkins as $checkin) {
            foreach ($checkin->fines as $fine) {
                $violations[] = [
                    'checkout_date' => $checkin->check_out_time?->format('d M Y H:i') ?? 'N/A',
                    'violation_name' => $fine->getTypeLabel(),
                    'fine_amount' => (float) $fine->amount,
                ];
            }
        }
        
        return response()->json([
            'violations' => $violations,
            'count' => count($violations),
        ]);
    } catch (\Exception $e) {
        Log::error('Violations API Error: ' . $e->getMessage());
        return response()->json([
            'violations' => [],
            'error' => $e->getMessage()
        ], 500);
    }
})->name('api.violations');
