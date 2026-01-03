<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMuaPortfoliosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mua_portfolios', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mua_profile_id');
            $table->string('image');
            $table->text('description')->nullable();
            $table->integer('order')->default(0);
            $table->boolean('is_featured')->default(false);
            $table->timestamps();

            $table->foreign('mua_profile_id')->references('id')->on('mua_profiles')->onDelete('cascade');
            // Note: mua_profile_id akan selalu 1 karena hanya ada satu MUA (WINWIN Makeup)
            $table->index('mua_profile_id');
            $table->index('is_featured');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mua_portfolios');
    }
}
