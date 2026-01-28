<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeRoomIdNullableInLockersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lockers', function (Blueprint $table) {
            // Drop foreign key constraint
            $table->dropForeign(['room_id']);
            
            // Make room_id nullable
            $table->unsignedBigInteger('room_id')->nullable()->change();
            
            // Add foreign key back as nullable
            $table->foreign('room_id')->references('id')->on('rooms')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lockers', function (Blueprint $table) {
            // Drop foreign key constraint
            $table->dropForeign(['room_id']);
            
            // Make room_id non-nullable again
            $table->unsignedBigInteger('room_id')->change();
            
            // Add foreign key back
            $table->foreign('room_id')->references('id')->on('rooms')->onDelete('cascade');
        });
    }
}
