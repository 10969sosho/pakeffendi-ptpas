<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('customer_addresses', function (Blueprint $table) {
            $table->string('province_code', 16)->nullable()->after('address');
            $table->string('province_name', 120)->nullable()->after('province_code');
            $table->string('regency_code', 16)->nullable()->after('province_name');
            $table->string('regency_name', 120)->nullable()->after('regency_code');
            $table->string('district_code', 16)->nullable()->after('regency_name');
            $table->string('district_name', 120)->nullable()->after('district_code');
            $table->string('village_code', 16)->nullable()->after('district_name');
            $table->string('village_name', 120)->nullable()->after('village_code');
        });
    }

    public function down(): void
    {
        Schema::table('customer_addresses', function (Blueprint $table) {
            $table->dropColumn([
                'province_code',
                'province_name',
                'regency_code',
                'regency_name',
                'district_code',
                'district_name',
                'village_code',
                'village_name',
            ]);
        });
    }
};
