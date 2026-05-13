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
            $table->foreignId('sales_id')->nullable()->constrained('users')->onDelete('set null');
        });

        Schema::table('sales_orders', function (Blueprint $table) {
            // Check if sales_id exists before adding
            if (!Schema::hasColumn('sales_orders', 'sales_id')) {
                $table->foreignId('sales_id')->nullable()->constrained('users')->onDelete('set null');
            }
            // Update status column to be enum-like (string with allowed values) and default 'new'
            // Since modifying enum in some DBs is hard, we keep it string but change default
            $table->string('status')->default('new')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropForeign(['sales_id']);
            $table->dropColumn('sales_id');
        });

        Schema::table('sales_orders', function (Blueprint $table) {
             if (Schema::hasColumn('sales_orders', 'sales_id')) {
                $table->dropForeign(['sales_id']);
                $table->dropColumn('sales_id');
            }
            $table->string('status')->default('Payment Pending')->change();
        });
    }
};
