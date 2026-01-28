<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Room;

class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $rooms = [];
        
        // Create 150 rooms (1-150)
        for ($i = 1; $i <= 150; $i++) {
            $rooms[] = [
                'room_number' => (string)$i,
                'capacity' => 1,
                'status' => 'tersedia',
                'description' => "Room $i - Single bed"
            ];
        }

        foreach ($rooms as $room) {
            Room::create($room);
        }
    }
}
