<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductVariantItem extends Model
{
    protected $fillable = [
        'product_id',
        'variant_1_id',
        'variant_2_id',
        'sku',
        'price_1',
        'qty_1',
        'disc_1',
        'qty_2',
        'disc_2',
        'qty_3',
        'disc_3',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'price_1' => 'decimal:2',
        'disc_1' => 'decimal:2',
        'disc_2' => 'decimal:2',
        'disc_3' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function variant1(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class, 'variant_1_id');
    }

    public function variant2(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class, 'variant_2_id');
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class, 'product_variant_item_id');
    }
}
