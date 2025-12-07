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
        $rooms = [
            ['room_number' => '101', 'capacity' => 1, 'status' => 'tersedia', 'description' => 'Single room'],
            ['room_number' => '102', 'capacity' => 1, 'status' => 'tersedia', 'description' => 'Single room'],
            ['room_number' => '103', 'capacity' => 2, 'status' => 'tersedia', 'description' => 'Double room'],
            ['room_number' => '104', 'capacity' => 2, 'status' => 'terisi', 'description' => 'Double room'],
            ['room_number' => '105', 'capacity' => 1, 'status' => 'perbaikan', 'description' => 'Single room - maintenance'],

            ['room_number' => '201', 'capacity' => 1, 'status' => 'tersedia', 'description' => 'Single room'],
            ['room_number' => '202', 'capacity' => 1, 'status' => 'tersedia', 'description' => 'Single room'],
            ['room_number' => '203', 'capacity' => 2, 'status' => 'tersedia', 'description' => 'Double room'],
            ['room_number' => '204', 'capacity' => 2, 'status' => 'tersedia', 'description' => 'Double room'],
            ['room_number' => '205', 'capacity' => 1, 'status' => 'terisi', 'description' => 'Single room'],

            ['room_number' => '301', 'capacity' => 1, 'status' => 'tersedia', 'description' => 'Single room'],
            ['room_number' => '302', 'capacity' => 1, 'status' => 'tersedia', 'description' => 'Single room'],
            ['room_number' => '303', 'capacity' => 2, 'status' => 'terisi', 'description' => 'Double room'],
            ['room_number' => '304', 'capacity' => 2, 'status' => 'tersedia', 'description' => 'Double room'],
            ['room_number' => '305', 'capacity' => 1, 'status' => 'tersedia', 'description' => 'Single room'],
        ];

        foreach ($rooms as $room) {
            Room::create($room);
        }
    }
}
