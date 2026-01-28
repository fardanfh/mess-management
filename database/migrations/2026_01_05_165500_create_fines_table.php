<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('checkin_id')->constrained()->onDelete('cascade');
            $table->string('fine_type'); // smoking, eating_drinking, drying_clothes, littering
            $table->decimal('amount', 12, 2); // Amount in Rp
            $table->text('description')->nullable();
            $table->foreignId('added_by')->constrained('users')->onDelete('restrict'); // Officer who added the fine
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fines');
    }
}
