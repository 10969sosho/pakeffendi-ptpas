<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\CustomerAddress;
use App\Models\Product;
use App\Models\SalesOrder;
use App\Models\SalesOrderItem;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;

class BuyerSeeder extends Seeder
{
    public function run(): void
    {
        $sales = User::query()->where('email', 'sales@example.com')->first();

        $buyer = Customer::query()->updateOrCreate(
            ['email' => 'buyer@example.com'],
            [
                'customer_code' => 'BUYER-000001',
                'full_name' => 'BUYER DEMO',
                'account_type' => 'Customer',
                'ktp_number' => '1000000000000000',
                'npwp' => null,
                'password' => Hash::make('password123'),
                'status' => 'active',
                'address' => 'Jl. Demo Buyer',
                'province' => 'DKI Jakarta',
                'city' => 'Jakarta',
                'postal_code' => '10110',
                'phone' => '081234567890',
                'contact_person' => 'BUYER DEMO',
                'company_name' => null,
                'internal_code' => null,
                'sales_id' => $sales?->id,
            ]
        );

        CustomerAddress::query()->updateOrCreate(
            [
                'customer_id' => $buyer->id,
                'label' => 'Alamat Utama',
            ],
            [
                'recipient_name' => $buyer->full_name,
                'phone' => $buyer->phone,
                'address' => (string) $buyer->address,
                'province' => $buyer->province,
                'city' => $buyer->city,
                'postal_code' => $buyer->postal_code,
                'is_active' => true,
            ],
        );

        if (! $sales) {
            return;
        }

        $existingOrderIds = SalesOrder::query()
            ->where('sales_id', $sales->id)
            ->where('customer_id', $buyer->id)
            ->where('notes', 'SEED-DUMMY')
            ->pluck('id')
            ->all();

        if ($existingOrderIds !== []) {
            SalesOrderItem::query()->whereIn('sales_order_id', $existingOrderIds)->delete();
            SalesOrder::query()->whereIn('id', $existingOrderIds)->delete();
        }

        $products = Product::query()->where('discontinued', false)->inRandomOrder()->limit(50)->get();
        if ($products->isEmpty()) {
            return;
        }

        $start = now()->subMonths(11)->startOfMonth()->startOfDay();
        $end = now()->endOfMonth()->endOfDay();

        for ($i = 1; $i <= 50; $i++) {
            $orderDate = Carbon::createFromTimestamp(rand($start->timestamp, $end->timestamp))->seconds(0);

            $order = SalesOrder::createWithNextOrderNo([
                'order_date' => $orderDate,
                'customer_id' => $buyer->id,
                'payment_type' => 'Transfer',
                'status' => [
                    SalesOrder::STATUS_NEW,
                    SalesOrder::STATUS_ON_PROGRESS,
                    SalesOrder::STATUS_ON_DELIVERY,
                    SalesOrder::STATUS_FINISHED,
                ][($i - 1) % 4],
                'sales_person_id' => $sales->id,
                'sales_id' => $sales->id,
                'shipping_fee' => 0,
                'grand_total' => 0,
                'dpp' => 0,
                'ppn' => 0,
                'ppn_percent' => 11,
                'process_date' => null,
                'process_time' => null,
                'process_order_no' => null,
                'notes' => 'SEED-DUMMY',
                'delivery_to' => $buyer->full_name,
                'delivery_address' => (string) $buyer->address,
                'delivery_phone' => $buyer->phone,
            ]);

            $picked = $products->random(min($products->count(), rand(1, 3)));
            $picked = $picked instanceof \Illuminate\Support\Collection ? $picked : collect([$picked]);

            $grandTotal = 0.0;

            foreach ($picked as $product) {
                $qty = rand(1, 8);
                $pricing = $product->pricingForQuantity($qty);
                $unitPrice = (float) $pricing['unit_price'];
                $discountPercent = (float) $pricing['discount_percent'];
                $netPrice = (float) $pricing['net_price'];
                $finalTotal = $netPrice * $qty;

                SalesOrderItem::query()->create([
                    'sales_order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'quantity' => $qty,
                    'unit_price' => $unitPrice,
                    'net_price' => $netPrice,
                    'discount_percent' => $discountPercent,
                    'final_total' => $finalTotal,
                ]);

                $grandTotal += $finalTotal;
            }

            $order->update([
                'grand_total' => $grandTotal,
                'dpp' => $grandTotal,
                'ppn' => $grandTotal * 0.11,
            ]);
        }
    }
}
