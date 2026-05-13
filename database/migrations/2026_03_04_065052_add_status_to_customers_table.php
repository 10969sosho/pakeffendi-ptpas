<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->string('status')->default('active')->after('password'); 
            // 'pending', 'active', 'rejected'
            
            // Allow nullable fields for registration
            $table->string('customer_code')->nullable()->change();
            $table->string('account_type')->nullable()->change();
            $table->string('ktp_number')->nullable()->change();
            $table->string('contact_person')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn('status');
            
            // Revert changes (might be tricky if data exists with nulls)
            // Ideally we don't revert nullable to not null without data cleanup
            // For now we just drop status
        });
    }
};
