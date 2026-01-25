<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class UpdatePaymentsTableForXendit extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payments', function (Blueprint $table) {
            // Add new columns (standard schema builder doesn't need DBAL for adding columns)
            if (!Schema::hasColumn('payments', 'external_id')) {
                $table->string('external_id')->nullable()->after('payment_code');
            }
            if (!Schema::hasColumn('payments', 'checkout_link')) {
                $table->string('checkout_link')->nullable()->after('external_id');
            }
        });

        // Use raw SQL to modify ENUM columns to VARCHAR or Expanded ENUM to avoid DBAL issues
        // Modifying ENUM to VARCHAR (String) for flexibility
        DB::statement("ALTER TABLE payments MODIFY COLUMN status VARCHAR(255) DEFAULT 'pending'");
        DB::statement("ALTER TABLE payments MODIFY COLUMN payment_method VARCHAR(255) DEFAULT 'bank_transfer'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn(['external_id', 'checkout_link']);
        });

        // Revert columns to ENUM (This might fail if data doesn't match, so be careful. 
        // For development, we revert, but in prod be cautious)
        DB::statement("ALTER TABLE payments MODIFY COLUMN status ENUM('pending', 'paid', 'verified', 'rejected') DEFAULT 'pending'");
        DB::statement("ALTER TABLE payments MODIFY COLUMN payment_method ENUM('bank_transfer', 'cash', 'e_wallet', 'other') DEFAULT 'bank_transfer'");
    }
}
