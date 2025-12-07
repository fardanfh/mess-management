<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use Illuminate\Http\Request;

class NFCController extends Controller
{
    /**
     * Simulate NFC card read
     *
     * This endpoint simulates reading an NFC ID Card.
     * In a real scenario, this would communicate with an NFC reader device.
     */
    public function read($id_card)
    {
        $driver = Driver::where('id_card', $id_card)->first();

        if (!$driver) {
            return response()->json([
                'status' => 'error',
                'message' => 'Card not recognized',
                'id_card' => $id_card,
                'scan_time' => now(),
            ], 404);
        }

        // Check if already checked in
        $isCheckedIn = $driver->isCheckedIn();

        return response()->json([
            'status' => 'success',
            'id_card' => $id_card,
            'driver_id' => $driver->id,
            'driver_name' => $driver->name,
            'driver_status' => $driver->status,
            'is_checked_in' => $isCheckedIn,
            'scan_time' => now(),
        ]);
    }

    /**
     * Simulate checking in via NFC
     */
    public function checkin(Request $request)
    {
        $validated = $request->validate([
            'id_card' => 'required|string',
            'room_id' => 'required|exists:rooms,id',
        ]);

        $driver = Driver::where('id_card', $validated['id_card'])->first();

        if (!$driver) {
            return response()->json([
                'status' => 'error',
                'message' => 'Driver not found',
            ], 404);
        }

        if ($driver->isCheckedIn()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Driver is already checked in',
            ], 400);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Ready for check-in',
            'driver' => $driver,
        ]);
    }

    /**
     * Simulate checking out via NFC
     */
    public function checkout(Request $request)
    {
        $validated = $request->validate([
            'id_card' => 'required|string',
        ]);

        $driver = Driver::where('id_card', $validated['id_card'])->first();

        if (!$driver) {
            return response()->json([
                'status' => 'error',
                'message' => 'Driver not found',
            ], 404);
        }

        $checkin = $driver->currentCheckin();

        if (!$checkin) {
            return response()->json([
                'status' => 'error',
                'message' => 'Driver is not checked in',
            ], 400);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Ready for check-out',
            'driver' => $driver,
            'current_checkin' => $checkin,
            'room' => $checkin->room,
        ]);
    }
}
