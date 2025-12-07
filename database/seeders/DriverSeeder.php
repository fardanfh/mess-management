<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Driver;

class DriverSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $drivers = [
            [
                'id_card' => 'DRV001',
                'name' => 'Budi Santoso',
                'phone' => '08123456789',
                'email' => 'budi@example.com',
                'address' => 'Jl. Merdeka No. 123, Jakarta',
                'status' => 'active',
            ],
            [
                'id_card' => 'DRV002',
                'name' => 'Ahmad Wijaya',
                'phone' => '08234567890',
                'email' => 'ahmad@example.com',
                'address' => 'Jl. Sudirman No. 456, Jakarta',
                'status' => 'active',
            ],
            [
                'id_card' => 'DRV003',
                'name' => 'Siti Nurhaliza',
                'phone' => '08345678901',
                'email' => 'siti@example.com',
                'address' => 'Jl. Gatot Subroto No. 789, Jakarta',
                'status' => 'active',
            ],
            [
                'id_card' => 'DRV004',
                'name' => 'Rudi Hermawan',
                'phone' => '08456789012',
                'email' => 'rudi@example.com',
                'address' => 'Jl. Terogong No. 321, Depok',
                'status' => 'active',
            ],
            [
                'id_card' => 'DRV005',
                'name' => 'Eka Putri',
                'phone' => '08567890123',
                'email' => 'eka@example.com',
                'address' => 'Jl. Ahmad Yani No. 654, Bekasi',
                'status' => 'active',
            ],
        ];

        foreach ($drivers as $driver) {
            Driver::create($driver);
        }
    }
}
