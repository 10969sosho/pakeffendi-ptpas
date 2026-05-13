<?php

namespace Database\Seeders;

use App\Models\ProductCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductCategorySeeder extends Seeder
{
    public const CATEGORIES = [
        'ABRASIVE GRINDING',
        'AIR TOOL & COMPRESSOR',
        'AUTOMOTIVE',
        'BUILDING TOOL',
        'CHEMICAL TAPE',
        'CLEANING',
        'CUTTING TOOL',
        'ELECTRIC TOOL',
        'FASTENER',
        'GARDEN TOOL',
        'GENERATOR ENGINE',
        'HAND TOOL',
        'KITCHEN',
        'LAIN LAIN',
        'LIFTING & WHEEL',
        'MACHINERY',
        'MEASURING',
        'PAINTING',
        'PIPE & FITTING',
        'POWER TOOL',
        'PUMP',
        'SAFETY',
        'SPARE PART',
        'WELDING',
    ];

    public function run(): void
    {
        foreach (self::CATEGORIES as $name) {
            $code = $this->generateCategoryCode($name);
            ProductCategory::query()->updateOrCreate(
                ['category_code' => $code],
                ['name' => $name, 'image_path' => null]
            );
        }
    }

    private function generateCategoryCode(string $name): string
    {
        $base = strtoupper(Str::slug($name, '_'));
        $base = $base === '' ? 'CAT' : $base;

        return substr($base, 0, 50);
    }
}
