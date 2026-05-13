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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('customer_code', 64)->unique();

            $table->string('full_name');
            $table->string('account_type');
            $table->string('ktp_number');
            $table->string('npwp')->nullable();

            $table->string('email', 191)->unique();
            $table->string('password');

            $table->text('address')->nullable();
            $table->string('province')->nullable();
            $table->string('city')->nullable();
            $table->string('postal_code')->nullable();

            $table->string('phone', 32)->unique();
            $table->string('contact_person');
            $table->string('company_name')->nullable();
            $table->string('internal_code')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
