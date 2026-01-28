<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class UpdateUsernameInUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Update existing users with username based on email prefix
        DB::statement('UPDATE users SET username = SUBSTRING_INDEX(email, "@", 1) WHERE username IS NULL OR username = ""');
        
        // Make username column unique and not nullable
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->nullable(false)->unique()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Rollback to nullable
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique('users_username_unique');
            $table->string('username')->nullable()->change();
        });
    }
}
