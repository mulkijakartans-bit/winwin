<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLoginBackgroundToMuaProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mua_profiles', function (Blueprint $table) {
            $table->string('login_background')->nullable()->after('hero_image');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mua_profiles', function (Blueprint $table) {
            $table->dropColumn('login_background');
        });
    }
}
