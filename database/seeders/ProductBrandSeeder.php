<?php

namespace Database\Seeders;

use App\Models\ProductBrand;
use Illuminate\Database\Seeder;

class ProductBrandSeeder extends Seeder
{
    public const BRANDS = [
        ['BOSCH', 'BOSCH', 'https://placehold.co/160x80/ffffff/111111?text=BOSCH'],
        ['WIPRO', 'WIPRO', 'https://placehold.co/160x80/ffffff/f57c00?text=WIPRO'],
        ['IKURA', 'IKURA', 'https://placehold.co/160x80/ffffff/ff9800?text=IKURA'],
        ['SHIMIZU', 'SHIMIZU', 'https://placehold.co/160x80/ffffff/2563eb?text=SHIMIZU'],
        ['NACHI', 'NACHI', 'https://placehold.co/160x80/ffffff/111111?text=NACHI'],
        ['3M', '3M', 'https://placehold.co/160x80/ffffff/ef4444?text=3M'],
        ['ALLWIN', 'ALLWIN', 'https://placehold.co/160x80/ffffff/ef4444?text=ALLWIN'],
        ['KOBE', 'KOBE', 'https://placehold.co/160x80/ffffff/2563eb?text=KOBE'],
        ['DONGCHENG', 'DONGCHENG', 'https://placehold.co/160x80/ffffff/ef4444?text=DONGCHENG'],
        ['TOKYO', 'TOKYO', 'https://placehold.co/160x80/ffffff/facc15?text=TOKYO'],
        ['MODERN', 'MODERN', 'https://placehold.co/160x80/ffffff/111111?text=MODERN'],
        ['NIKKO', 'NIKKO', 'https://placehold.co/160x80/ffffff/ef4444?text=NIKKO'],
    ];

    public function run(): void
    {
        foreach (self::BRANDS as $row) {
            ProductBrand::query()->updateOrCreate(
                ['brand_code' => $row[0]],
                [
                    'brand_name' => $row[1],
                    'brand_image_path' => $row[2],
                ]
            );
        }
    }
}

