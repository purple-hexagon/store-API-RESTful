<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Product;
use App\Models\Shop;
use App\Models\ShopProduct;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $shopCount = 3;
        $productCount = 4;

        // Shop
        Shop::factory($shopCount)->create();

        // Product
        Product::factory($productCount)->create();

        // ShopProduct
        for ($shopIndex = 1; $shopIndex <= $shopCount; $shopIndex++) {
            for ($productIndex = 1; $productIndex <= $productCount; $productIndex++) {
                ShopProduct::factory()->create([
                    'shop_id' => $shopIndex,
                    'product_id' => $productIndex,
                    'quantity' => fake()->unique()->numberBetween(0, ($shopCount * $productCount) - 1 )
                ]);
            }
        }

        // Shop with ShopProduct for API test.

        $shop = new Shop();
        $shop->name = 'Zaragoza';
        $shop->save();
        ShopProduct::factory()->create([
            'shop_id' => $shopCount+1, // Zaragoza ID
            'product_id' => 1,
            'quantity' => 0
        ]);
        ShopProduct::factory()->create([
            'shop_id' => $shopCount+1, // Zaragoza ID
            'product_id' => 2,
            'quantity' => 4
        ]);
    }
}
