<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('product_variant_items');

        Schema::create('product_variant_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->index();
            $table->foreignId('variant_1_id')->index();
            $table->foreignId('variant_2_id')->nullable()->index();
            $table->string('sku')->nullable();

            $table->decimal('price_1', 14, 2);
            $table->unsignedInteger('qty_1')->nullable();
            $table->decimal('disc_1', 6, 2)->nullable();
            $table->unsignedInteger('qty_2')->nullable();
            $table->decimal('disc_2', 6, 2)->nullable();
            $table->unsignedInteger('qty_3')->nullable();
            $table->decimal('disc_3', 6, 2)->nullable();

            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->unique(['product_id', 'variant_1_id', 'variant_2_id'], 'pvi_prod_v1_v2_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_variant_items');
    }
};
