<?php

namespace App\Http\Controllers;

use App\Models\Locker;
use App\Models\Room;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class LockerController extends Controller
{
    /**
     * Display a listing of lockers.
     */
    public function index()
    {
        // Show all lockers - pagination handled by DataTables
        $lockers = Locker::with('room')->get();
        return view('lockers.index', compact('lockers'));
    }

    /**
     * Show the form for creating a new locker.
     */
    public function create()
    {
        $rooms = Room::all();
        return view('lockers.create', compact('rooms'));
    }

    /**
     * Store a newly created locker in database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'locker_number' => 'required|unique:lockers|string|max:50',
            'room_id' => 'required|exists:rooms,id',
            'capacity' => 'required|integer|min:1|max:5',
            'status' => 'required|in:tersedia,penuh,perbaikan',
            'description' => 'nullable|string',
        ]);

        $locker = Locker::create($validated);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'create',
            'model_type' => 'Locker',
            'model_id' => $locker->id,
            'description' => "Created locker: {$locker->locker_number}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('lockers.index')->with('success', 'Locker created successfully');
    }

    /**
     * Display the specified locker.
     */
    public function show(Locker $locker)
    {
        $locker->load(['room', 'checkins.driver']);
        return view('lockers.show', compact('locker'));
    }

    /**
     * Show the form for editing the specified locker.
     */
    public function edit(Locker $locker)
    {
        $rooms = Room::all();
        return view('lockers.edit', compact('locker', 'rooms'));
    }

    /**
     * Update the specified locker in database.
     */
    public function update(Request $request, Locker $locker)
    {
        $validated = $request->validate([
            'locker_number' => 'required|unique:lockers,locker_number,' . $locker->id . '|string|max:50',
            'room_id' => 'required|exists:rooms,id',
            'capacity' => 'required|integer|min:1|max:5',
            'status' => 'required|in:tersedia,penuh,perbaikan',
            'description' => 'nullable|string',
        ]);

        $oldData = $locker->toArray();
        $locker->update($validated);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'update',
            'model_type' => 'Locker',
            'model_id' => $locker->id,
            'description' => "Updated locker: {$locker->locker_number}",
            'changes' => json_encode([
                'old' => $oldData,
                'new' => $locker->toArray(),
            ]),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('lockers.show', $locker)->with('success', 'Locker updated successfully');
    }

    /**
     * Remove the specified locker from database.
     */
    public function destroy(Request $request, Locker $locker)
    {
        $lockerNumber = $locker->locker_number;
        $locker->delete();

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'delete',
            'model_type' => 'Locker',
            'model_id' => $locker->id,
            'description' => "Deleted locker: {$lockerNumber}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('lockers.index')->with('success', 'Locker deleted successfully');
    }

    /**
     * Get available lockers (AJAX)
     */
    public function available(Request $request)
    {
        $validated = $request->validate([
            'room_id' => 'nullable|exists:rooms,id',
        ]);

        $query = Locker::where('status', '!=', 'perbaikan');

        if ($validated['room_id'] ?? false) {
            $query->where('room_id', $validated['room_id']);
        }

        $lockers = $query->get()
            ->filter(function ($locker) {
                return $locker->canAccommodateMore();
            })
            ->values();

        return response()->json([
            'status' => 'success',
            'lockers' => $lockers,
        ]);
    }
}
