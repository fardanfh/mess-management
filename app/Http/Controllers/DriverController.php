<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class DriverController extends Controller
{
    /**
     * Display a listing of the drivers.
     */
    public function index()
    {
        $drivers = Driver::paginate(10);
        return view('drivers.index', compact('drivers'));
    }

    /**
     * Show the form for creating a new driver.
     */
    public function create()
    {
        return view('drivers.create');
    }

    /**
     * Store a newly created driver in database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_card' => 'required|unique:drivers|string|max:50',
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
        ]);

        $driver = Driver::create($validated);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'create',
            'model_type' => 'Driver',
            'model_id' => $driver->id,
            'description' => "Created driver: {$driver->name}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('drivers.index')->with('success', 'Driver created successfully');
    }

    /**
     * Display the specified driver.
     */
    public function show(Driver $driver)
    {
        return view('drivers.show', compact('driver'));
    }

    /**
     * Show the form for editing the specified driver.
     */
    public function edit(Driver $driver)
    {
        return view('drivers.edit', compact('driver'));
    }

    /**
     * Update the specified driver in database.
     */
    public function update(Request $request, Driver $driver)
    {
        $validated = $request->validate([
            'id_card' => 'required|unique:drivers,id_card,' . $driver->id . '|string|max:50',
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        $changes = $driver->getChanges();
        $driver->update($validated);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'update',
            'model_type' => 'Driver',
            'model_id' => $driver->id,
            'description' => "Updated driver: {$driver->name}",
            'changes' => $changes,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('drivers.show', $driver)->with('success', 'Driver updated successfully');
    }

    /**
     * Remove the specified driver from database.
     */
    public function destroy(Request $request, Driver $driver)
    {
        $name = $driver->name;
        $driver->delete();

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'delete',
            'model_type' => 'Driver',
            'model_id' => $driver->id,
            'description' => "Deleted driver: {$name}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('drivers.index')->with('success', 'Driver deleted successfully');
    }

    /**
     * Search drivers by name or ID card
     */
    public function search(Request $request)
    {
        $query = $request->get('q');

        $drivers = Driver::where('name', 'like', "%{$query}%")
            ->orWhere('id_card', 'like', "%{$query}%")
            ->paginate(10)
            ->appends($request->query());

        return view('drivers.index', compact('drivers'));
    }

    /**
     * Generate a unique ID Card for driver
     */
    public function generateIdCard()
    {
        // Generate format: DRV-XXXXX (5 random numbers)
        do {
            $randomNum = str_pad(random_int(1, 99999), 5, '0', STR_PAD_LEFT);
            $idCard = 'DRV-' . $randomNum;
        } while (Driver::where('id_card', $idCard)->exists());

        return response()->json([
            'id_card' => $idCard
        ]);
    }
}
