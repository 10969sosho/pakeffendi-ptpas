<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Model;

class ProductBrand extends Model
{
    protected $primaryKey = 'brand_code';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'brand_code',
        'brand_name',
        'brand_image_path',
    ];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'product_brand_code', 'brand_code');
    }

    public function favorite(): HasOne
    {
        return $this->hasOne(FavoriteBrand::class, 'product_brand_code', 'brand_code');
    }
}
