<?php

namespace Database\Seeders;

use App\Models\Stock;
use App\Models\Variation;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class StockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // Get all variations
        $variations = Variation::all();

        // Loop through each variation
        foreach ($variations as $variation) {
            // Create a stock entry for each variation
            Stock::create([
                'barcode' => $variation->barcode,
                'branch_id' => 1, // Assuming you have a branch with id 1
                'product_id' => $variation->product_id,
                'variation_id' => $variation->id,
                'warehouse_id' => $faker->numberBetween(1, 10), // Assuming you have warehouses with ids 1 to 10
                'rack_id' => $faker->numberBetween(1, 50), // Assuming you have racks with ids 1 to 50
                'stock_quantity' => $faker->numberBetween(0, 100),
                'stock_age' => $faker->randomElement(['new', 'old', 'very old']),
                'is_Current_stock' => true,
                'status' => $faker->randomElement(['stock_out', 'available', 'low_stock']),
            ]);
        }
    }
}
