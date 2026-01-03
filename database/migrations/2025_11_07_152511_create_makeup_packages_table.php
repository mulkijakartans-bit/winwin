<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMakeupPackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('makeup_packages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mua_profile_id');
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->integer('duration')->comment('Duration in minutes');
            $table->text('includes')->nullable()->comment('What is included in this package');
            $table->boolean('is_active')->default(true);
            $table->integer('order')->default(0);
            $table->timestamps();

            $table->foreign('mua_profile_id')->references('id')->on('mua_profiles')->onDelete('cascade');
            // Note: mua_profile_id akan selalu 1 karena hanya ada satu MUA (WINWIN Makeup)
            $table->index('mua_profile_id');
            $table->index('is_active');
            $table->index('price');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('makeup_packages');
    }
}
