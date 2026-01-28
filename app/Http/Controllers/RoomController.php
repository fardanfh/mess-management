<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    /**
     * Display a listing of the rooms.
     */
    public function index()
    {
        $rooms = Room::get();
        $statusCounts = [
            'tersedia' => Room::where('status', 'tersedia')->count(),
            'terisi' => Room::where('status', 'terisi')->count(),
            'perbaikan' => Room::where('status', 'perbaikan')->count(),
        ];
        return view('rooms.index', compact('rooms', 'statusCounts'));
    }

    /**
     * Show the form for creating a new room.
     */
    public function create()
    {
        return view('rooms.create');
    }

    /**
     * Store a newly created room in database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'room_number' => 'required|unique:rooms|string|max:50',
            'capacity' => 'required|integer|min:1',
            'status' => 'required|in:tersedia,terisi,perbaikan',
            'description' => 'nullable|string',
        ]);

        $room = Room::create($validated);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'create',
            'model_type' => 'Room',
            'model_id' => $room->id,
            'description' => "Created room: {$room->room_number}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('rooms.index')->with('success', 'Room created successfully');
    }

    /**
     * Display the specified room.
     */
    public function show(Room $room)
    {
        $room->load('checkins.driver');
        return view('rooms.show', compact('room'));
    }

    /**
     * Show the form for editing the specified room.
     */
    public function edit(Room $room)
    {
        return view('rooms.edit', compact('room'));
    }

    /**
     * Update the specified room in database.
     */
    public function update(Request $request, Room $room)
    {
        $validated = $request->validate([
            'room_number' => 'required|unique:rooms,room_number,' . $room->id . '|string|max:50',
            'capacity' => 'required|integer|min:1',
            'status' => 'required|in:tersedia,terisi,perbaikan',
            'description' => 'nullable|string',
        ]);

        $changes = $room->getChanges();
        $room->update($validated);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'update',
            'model_type' => 'Room',
            'model_id' => $room->id,
            'description' => "Updated room: {$room->room_number}",
            'changes' => $changes,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('rooms.show', $room)->with('success', 'Room updated successfully');
    }

    /**
     * Remove the specified room from database.
     */
    public function destroy(Request $request, Room $room)
    {
        $roomNumber = $room->room_number;
        $room->delete();

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'delete',
            'model_type' => 'Room',
            'model_id' => $room->id,
            'description' => "Deleted room: {$roomNumber}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('rooms.index')->with('success', 'Room deleted successfully');
    }

    /**
     * Get available rooms (API)
     */
    public function available()
    {
        $rooms = Room::where('status', 'tersedia')->get();
        return response()->json($rooms);
    }
}
