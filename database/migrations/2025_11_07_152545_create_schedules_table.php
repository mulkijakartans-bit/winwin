<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mua_profile_id');
            $table->date('date');
            $table->time('start_time');
            $table->time('end_time');
            $table->boolean('is_available')->default(true);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('mua_profile_id')->references('id')->on('mua_profiles')->onDelete('cascade');
            // Note: mua_profile_id akan selalu 1 karena hanya ada satu MUA (WINWIN Makeup)
            $table->index('mua_profile_id');
            $table->index('date');
            $table->index('is_available');
            $table->unique(['mua_profile_id', 'date', 'start_time']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('schedules');
    }
}
