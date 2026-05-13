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
        Schema::table('product_images', function (Blueprint $table) {
            $table->foreign('product_id')->references('id')->on('products')->cascadeOnDelete();
        });

        Schema::table('favorite_brands', function (Blueprint $table) {
            $table->foreign('product_brand_code')->references('brand_code')->on('product_brands')->cascadeOnDelete();
        });

        Schema::table('sales_order_items', function (Blueprint $table) {
            $table->foreign('sales_order_id')->references('id')->on('sales_orders')->cascadeOnDelete();
            $table->foreign('product_id')->references('id')->on('products');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_images', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
        });

        Schema::table('favorite_brands', function (Blueprint $table) {
            $table->dropForeign(['product_brand_code']);
        });

        Schema::table('sales_order_items', function (Blueprint $table) {
            $table->dropForeign(['sales_order_id']);
            $table->dropForeign(['product_id']);
        });
    }
};
