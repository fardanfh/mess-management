<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;
use App\Models\User;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create roles
        $petugas = Role::create([
            'name' => 'Petugas',
            'description' => 'Petugas yang mengelola check-in/out dan data operasional',
        ]);

        $management = Role::create([
            'name' => 'Management',
            'description' => 'Management yang melihat laporan dan dashboard',
        ]);

        // Create permissions
        $permissions = [
            ['name' => 'manage_drivers', 'description' => 'Kelola data pengemudi'],
            ['name' => 'manage_rooms', 'description' => 'Kelola data kamar'],
            ['name' => 'process_checkin', 'description' => 'Proses check-in'],
            ['name' => 'process_checkout', 'description' => 'Proses check-out'],
            ['name' => 'view_dashboard', 'description' => 'Lihat dashboard'],
            ['name' => 'view_reports', 'description' => 'Lihat laporan'],
            ['name' => 'manage_payments', 'description' => 'Kelola pembayaran'],
            ['name' => 'view_activity_logs', 'description' => 'Lihat activity logs'],
        ];

        foreach ($permissions as $permission) {
            Permission::create($permission);
        }

        // Attach permissions to roles
        $petugas->permissions()->attach([1, 2, 3, 4, 7]);
        $management->permissions()->attach([5, 6, 8]);

        // Create default users
        $petugasUser = User::create([
            'name' => 'Petugas Demo',
            'email' => 'petugas@example.com',
            'password' => bcrypt('password'),
            'role_id' => $petugas->id,
        ]);

        $managementUser = User::create([
            'name' => 'Management Demo',
            'email' => 'management@example.com',
            'password' => bcrypt('password'),
            'role_id' => $management->id,
        ]);
    }
}
