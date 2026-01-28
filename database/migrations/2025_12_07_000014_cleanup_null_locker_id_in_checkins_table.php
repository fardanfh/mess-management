<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CleanupNullLockerIdInCheckinsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Fix checked_in records that don't have locker_id (from before locker module)
        // Assign them to the first available locker in their room
        $orphanedCheckins = DB::table('checkins')
            ->where('status', 'checked_in')
            ->whereNull('locker_id')
            ->get();

        foreach ($orphanedCheckins as $checkin) {
            // Find an available locker in the same room
            $availableLocker = DB::table('lockers')
                ->where('room_id', $checkin->room_id)
                ->where('status', '!=', 'perbaikan')
                ->first();

            if ($availableLocker) {
                DB::table('checkins')
                    ->where('id', $checkin->id)
                    ->update(['locker_id' => $availableLocker->id]);
            }
        }

        // Soft delete checkins yang sudah checked_out dan locker_id NULL
        DB::table('checkins')
            ->where('status', 'checked_out')
            ->whereNull('locker_id')
            ->update(['deleted_at' => now()]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Restore if needed
        DB::table('checkins')
            ->where('status', 'checked_out')
            ->whereNotNull('deleted_at')
            ->update(['deleted_at' => null]);
    }
}
