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
        Schema::create('sales_orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_no')->unique();
            $table->dateTime('order_date')->nullable();
            $table->foreignId('customer_id')->constrained();

            $table->string('payment_type')->nullable();
            $table->string('status')->default('Payment Pending');
            $table->foreignId('sales_person_id')->nullable()->constrained('users');

            $table->decimal('shipping_fee', 14, 2)->default(0);
            $table->decimal('grand_total', 14, 2)->default(0);
            $table->decimal('dpp', 14, 2)->default(0);
            $table->decimal('ppn', 14, 2)->default(0);
            $table->decimal('ppn_percent', 6, 2)->default(0);

            $table->date('process_date')->nullable();
            $table->time('process_time')->nullable();
            $table->string('process_order_no')->nullable();
            $table->text('notes')->nullable();

            $table->string('delivery_to')->nullable();
            $table->text('delivery_address')->nullable();
            $table->string('delivery_phone')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_orders');
    }
};
