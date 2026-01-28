<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Locker;
use App\Models\Room;

class LockerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create 92 lockers (1-92) without room_id
        for ($i = 1; $i <= 92; $i++) {
            Locker::create([
                'locker_number' => (string)$i,
                'room_id' => null,
                'capacity' => 2,
                'status' => 'tersedia',
                'description' => "Locker $i - 2 capacity"
            ]);
        }
    }
}
