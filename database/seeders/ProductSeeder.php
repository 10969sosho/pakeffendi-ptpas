<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductBrand;
use App\Models\ProductCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            ProductCategorySeeder::class,
            ProductBrandSeeder::class,
        ]);

        // Seed Static Products (Real Examples)
        $this->seedStaticProducts();

        $faker = fake('id_ID');
        $categories = ProductCategory::query()->orderBy('name')->get();
        $brands = ProductBrand::query()->orderBy('brand_name')->get();

        $perCategory = 8;
        $seq = 1;

        foreach ($categories as $category) {
            for ($i = 1; $i <= $perCategory; $i++) {
                $brand = $brands->random();
                $sku = 'SKU-'.str_pad((string) $seq, 6, '0', STR_PAD_LEFT);

                // Check if SKU already exists (from static products)
                if (Product::where('sku', $sku)->exists()) {
                    $seq++;

                    continue;
                }

                $name = strtoupper($category->name.' '.$faker->words($faker->numberBetween(2, 5), true));
                $photoText = urlencode($brand->brand_name.' '.Str::limit($name, 18, ''));
                $photo = 'https://placehold.co/600x600/ffffff/111111?text='.$photoText;

                $product = Product::query()->updateOrCreate(
                    ['sku' => $sku],
                    [
                        'name' => $name,
                        'variant' => '',
                        'product_brand_code' => $brand->brand_code,
                        'product_category_code' => $category->category_code,
                        'description' => $faker->paragraphs(3, true),
                        'unit' => 'pcs',
                        'weight_kg' => $faker->randomFloat(2, 0.1, 25),
                        'discontinued' => false,
                        'photo_path' => $photo,
                        'price_1' => $faker->numberBetween(15000, 7500000),
                        'qty_1' => 5,
                        'disc_1' => 0,
                        'qty_2' => 15,
                        'disc_2' => 0,
                        'qty_3' => 30,
                        'disc_3' => 0,
                    ]
                );

                $seq++;
            }
        }
    }

    private function seedStaticProducts(): void
    {
        $staticProducts = [
            [
                'sku' => 'BOSCH-GSB120',
                'name' => 'BOSCH GSB 120-LI GEN 2 CORDLESS IMPACT DRILL',
                'brand' => 'BOSCH',
                'category' => 'POWER TOOL',
                'price' => 1250000,
                'weight' => 2.5,
                'desc' => 'Bor baterai Bosch GSB 120-LI Gen 2 yang tangguh dan fleksibel untuk menyekrup dan mengebor.',
            ],
            [
                'sku' => 'WIPRO-W6130',
                'name' => 'WIPRO W6130 MESIN BOR DUDUK 13MM',
                'brand' => 'WIPRO',
                'category' => 'MACHINERY',
                'price' => 1850000,
                'weight' => 15.0,
                'desc' => 'Mesin bor duduk Wipro W6130 kapasitas 13mm, cocok untuk bengkel dan industri kecil.',
            ],
            [
                'sku' => 'DONGCHENG-DJZ16A',
                'name' => 'DONGCHENG DJZ16A ELECTRIC DRILL',
                'brand' => 'DONGCHENG',
                'category' => 'POWER TOOL',
                'price' => 650000,
                'weight' => 3.0,
                'desc' => 'Bor listrik Dongcheng DJZ16A dengan performa tinggi dan harga terjangkau.',
            ],
            [
                'sku' => 'MODERN-M2100C',
                'name' => 'MODERN M-2100C BOR LISTRIK',
                'brand' => 'MODERN',
                'category' => 'POWER TOOL',
                'price' => 350000,
                'weight' => 1.8,
                'desc' => 'Bor listrik Modern M-2100C, ringan dan mudah digunakan untuk keperluan rumah tangga.',
            ],
            [
                'sku' => 'SHIMIZU-PS135',
                'name' => 'SHIMIZU PS-135 E POMPA AIR OTOMATIS',
                'brand' => 'SHIMIZU',
                'category' => 'PUMP',
                'price' => 550000,
                'weight' => 9.0,
                'desc' => 'Pompa air otomatis Shimizu PS-135 E, handal dan tahan lama.',
            ],
        ];

        foreach ($staticProducts as $p) {
            $brand = ProductBrand::where('brand_name', $p['brand'])->first();
            $category = ProductCategory::where('name', $p['category'])->first();

            if (! $brand || ! $category) {
                continue;
            }

            $product = Product::query()->updateOrCreate(
                ['sku' => $p['sku']],
                [
                    'name' => $p['name'],
                    'variant' => '',
                    'product_brand_code' => $brand->brand_code,
                    'product_category_code' => $category->category_code,
                    'description' => $p['desc'],
                    'unit' => 'pcs',
                    'weight_kg' => $p['weight'],
                    'discontinued' => false,
                    'photo_path' => 'https://placehold.co/600x600/ffffff/111111?text='.urlencode($p['name']),
                    'price_1' => $p['price'],
                    'qty_1' => 1,
                    'disc_1' => 0,
                    'qty_2' => 5,
                    'disc_2' => 2,
                    'qty_3' => 10,
                    'disc_3' => 5,
                ]
            );
        }
    }
}
