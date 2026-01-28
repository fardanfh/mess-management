<?php

require __DIR__ . '/bootstrap/app.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

$status = $kernel->handle(
    $input = new Symfony\Component\Console\Input\StringInput('tinker'),
    new Symfony\Component\Console\Output\BufferedOutput,
);

// Now we have tinker running, let's run our debug code
echo "=== LOCKER OCCUPANCY DEBUG ===\n\n";

// Check if lockers table exists
try {
    $lockers = \App\Models\Locker::all();
    echo "✓ Lockers table found: " . count($lockers) . " lockers\n\n";
    
    foreach ($lockers as $locker) {
        echo "Locker ID: {$locker->id} - {$locker->locker_number}\n";
        echo "  Status: {$locker->status}\n";
        echo "  Capacity: {$locker->capacity}\n";
        echo "  Current Occupancy: {$locker->getCurrentOccupancy()}\n";
        
        // Check raw data
        $checkins = \Illuminate\Support\Facades\DB::table('checkins')
            ->where('locker_id', $locker->id)
            ->where('status', 'checked_in')
            ->get();
        
        echo "  Raw checked_in count: " . count($checkins) . "\n";
        
        if (count($checkins) > 0) {
            foreach ($checkins as $checkin) {
                $driver = \App\Models\Driver::find($checkin->driver_id);
                echo "    - Driver: {$driver->name} (ID: {$checkin->driver_id})\n";
            }
        }
        echo "\n";
    }
    
} catch (\Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
}

// Check if there are any checked_in checkins without locker_id
echo "\n=== CHECK FOR MISSING LOCKER_ID ===\n";
try {
    $nullLockerCheckins = \Illuminate\Support\Facades\DB::table('checkins')
        ->where('status', 'checked_in')
        ->whereNull('locker_id')
        ->get();
    
    echo "Checked_in checkins without locker_id: " . count($nullLockerCheckins) . "\n";
    
    if (count($nullLockerCheckins) > 0) {
        echo "WARNING: Found " . count($nullLockerCheckins) . " active checkins without locker_id!\n";
        echo "These should be assigned a locker or checked out.\n";
    }
    
} catch (\Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
}
