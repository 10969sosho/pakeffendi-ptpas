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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('sku')->unique();
            $table->string('name');
            $table->string('product_brand_code', 50);
            $table->string('product_category_code', 50);

            $table->longText('description');
            $table->string('unit');
            $table->decimal('weight_kg', 10, 2)->default(0);
            $table->boolean('discontinued')->default(false);
            $table->string('photo_path')->nullable();

            $table->decimal('price_1', 14, 2);
            $table->unsignedInteger('qty_1')->nullable();
            $table->decimal('disc_1', 6, 2)->nullable();
            $table->unsignedInteger('qty_2')->nullable();
            $table->decimal('disc_2', 6, 2)->nullable();
            $table->unsignedInteger('qty_3')->nullable();
            $table->decimal('disc_3', 6, 2)->nullable();
            $table->timestamps();

            $table->foreign('product_brand_code')->references('brand_code')->on('product_brands');
            $table->foreign('product_category_code')->references('category_code')->on('product_categories');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
