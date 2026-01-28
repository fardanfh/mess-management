<?php

namespace App\Http\Controllers;

use App\Models\Checkin;
use App\Models\Driver;
use App\Models\Room;
use App\Models\Locker;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CheckinController extends Controller
{
    /**
     * Display a listing of the checkins.
     */
    public function index()
    {
        $checkins = Checkin::with(['driver', 'room', 'locker', 'user'])->paginate(15);
        return view('checkins.index', compact('checkins'));
    }

    /**
     * Show the form for creating a new checkin.
     */
    public function create()
    {
        $drivers = Driver::where('status', 'active')->get();
        $availableRooms = Room::where('status', 'tersedia')->get();
        $availableLockers = Locker::where('status', '!=', 'perbaikan')
            ->get()
            ->filter(function ($locker) {
                return $locker->canAccommodateMore();
            })
            ->map(function ($locker) {
                // Add current occupancy to each locker for frontend display
                $locker->current_occupancy = $locker->getCurrentOccupancy();
                return $locker;
            })
            ->values();
        return view('checkins.create', compact('drivers', 'availableRooms', 'availableLockers'));
    }

    /**
     * Simulate NFC read and get driver data
     */
    public function scanCard(Request $request)
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

        // Check if driver is already checked in
        if ($driver->isCheckedIn()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Driver is already checked in',
            ], 400);
        }

        return response()->json([
            'status' => 'success',
            'driver' => $driver,
            'message' => 'Driver found',
        ]);
    }

    /**
     * Get available lockers for a room
     */
    public function getAvailableLockers(Request $request)
    {
        $validated = $request->validate([
            'room_id' => 'required|exists:rooms,id',
        ]);

        $lockers = Locker::where('room_id', $validated['room_id'])
            ->where('status', '!=', 'perbaikan')
            ->get()
            ->filter(function ($locker) {
                return $locker->canAccommodateMore();
            })
            ->values();

        return response()->json([
            'status' => 'success',
            'lockers' => $lockers,
        ]);
    }

    /**
     * Store a newly created checkin in database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'driver_id' => 'required|exists:drivers,id',
            'room_id' => 'required|exists:rooms,id',
            'locker_id' => 'required|exists:lockers,id',
            'check_in_time' => 'required|date_format:Y-m-d H:i',
        ]);

        $driver = Driver::findOrFail($validated['driver_id']);
        $room = Room::findOrFail($validated['room_id']);
        $locker = Locker::findOrFail($validated['locker_id']);

        // Validation: driver cannot check in twice
        if ($driver->isCheckedIn()) {
            return redirect()->back()->with('error', 'Driver is already checked in');
        }

        // Validation: room cannot be occupied if at capacity
        if (!$room->canAccommodateMore() && $room->status !== 'tersedia') {
            return redirect()->back()->with('error', 'Room is at full capacity');
        }

        // Validation: locker cannot be occupied if at capacity
        if (!$locker->canAccommodateMore()) {
            return redirect()->back()->with('error', 'Locker is at full capacity');
        }

        // Create checkin record
        $checkin = Checkin::create([
            'driver_id' => $driver->id,
            'room_id' => $room->id,
            'locker_id' => $locker->id,
            'user_id' => auth()->id(),
            'check_in_time' => $validated['check_in_time'],
            'status' => 'checked_in',
        ]);

        // Update room status if it was available
        if ($room->status === 'tersedia') {
            $room->update(['status' => 'terisi']);
        }

        // Update locker status based on occupancy
        $locker->updateStatus();

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'checkin',
            'model_type' => 'Checkin',
            'model_id' => $checkin->id,
            'description' => "Driver {$driver->name} checked in to room {$room->room_number} with locker {$locker->locker_number}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('checkins.index')->with('success', 'Check-in recorded successfully');
    }

    /**
     * Display the specified checkin.
     */
    public function show(Checkin $checkin)
    {
        $checkin->load(['driver', 'room', 'locker', 'user', 'checkout']);
        return view('checkins.show', compact('checkin'));
    }

    /**
     * Get checkout form for a checkin
     */
    public function getCheckoutForm(Checkin $checkin)
    {
        if ($checkin->status === 'checked_out') {
            return redirect()->back()->with('error', 'This check-in is already checked out');
        }

        return view('checkins.checkout', compact('checkin'));
    }

    /**
     * Show the form for adding a fine to a checkin
     */
    public function addFineForm(Checkin $checkin)
    {
        if ($checkin->status !== 'checked_in') {
            return redirect()->route('checkins.show', $checkin->id)
                ->with('error', 'Hanya dapat menambahkan denda untuk checkin yang masih aktif');
        }

        return view('fines.create', compact('checkin'));
    }
}
