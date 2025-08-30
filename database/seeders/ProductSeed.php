<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Color;
use App\Models\Product;
use App\Models\Psize;
use App\Models\Stock;
use App\Models\SubCategory;
use App\Models\Unit;
use App\Models\Variation;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Disable foreign key checks to avoid constraint issues during truncation
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Truncate tables
        Stock::truncate();
        Variation::truncate();
        Product::truncate();

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $faker = Faker::create();

        // Define electronics product categories and their data
        $electronicsProducts = [
            'Smartphones' => [
                'products' => [
                    ['name' => 'Pro Smartphone', 'variants' => ['64GB', '128GB', '256GB'], 'unit' => 'GB', 'regular_price' => 60000],
                    ['name' => 'Ultra Smartphone', 'variants' => ['128GB', '256GB', '512GB'], 'unit' => 'GB', 'regular_price' => 80000],
                    ['name' => 'Budget Smartphone', 'variants' => ['32GB', '64GB'], 'unit' => 'GB', 'regular_price' => 20000],
                    ['name' => 'Gaming Smartphone', 'variants' => ['128GB', '256GB'], 'unit' => 'GB', 'regular_price' => 70000],
                    ['name' => 'Foldable Smartphone', 'variants' => ['256GB', '512GB'], 'unit' => 'GB', 'regular_price' => 120000],
                ],
                'attributes' => [
                    'color' => 'Black, Silver, Blue',
                    'size' => 'Standard',
                    'weight' => '180g, 200g',
                ],
            ],
            'Laptops' => [
                'products' => [
                    ['name' => 'Ultrabook', 'variants' => ['8GB', '16GB', '32GB'], 'unit' => 'RAM', 'regular_price' => 90000],
                    ['name' => 'Gaming Laptop', 'variants' => ['16GB', '32GB'], 'unit' => 'RAM', 'regular_price' => 150000],
                    ['name' => 'Business Laptop', 'variants' => ['8GB', '16GB'], 'unit' => 'RAM', 'regular_price' => 80000],
                    ['name' => 'Convertible Laptop', 'variants' => ['16GB', '32GB'], 'unit' => 'RAM', 'regular_price' => 100000],
                    ['name' => 'Budget Laptop', 'variants' => ['4GB', '8GB'], 'unit' => 'RAM', 'regular_price' => 40000],
                ],
                'attributes' => [
                    'color' => 'Silver, Black',
                    'size' => '13inch, 15inch',
                    'weight' => '1.2kg, 2kg',
                ],
            ],
            'Headphones' => [
                'products' => [
                    ['name' => 'Wireless Earbuds', 'variants' => ['Standard', 'Pro'], 'unit' => 'type', 'regular_price' => 5000],
                    ['name' => 'Over-Ear Headphones', 'variants' => ['Wired', 'Wireless'], 'unit' => 'type', 'regular_price' => 10000],
                    ['name' => 'Noise-Cancelling Headphones', 'variants' => ['Standard', 'Premium'], 'unit' => 'type', 'regular_price' => 15000],
                    ['name' => 'Sports Earbuds', 'variants' => ['Basic', 'Waterproof'], 'unit' => 'type', 'regular_price' => 4000],
                    ['name' => 'Studio Headphones', 'variants' => ['Wired', 'Wireless'], 'unit' => 'type', 'regular_price' => 12000],
                ],
                'attributes' => [
                    'color' => 'Black, White',
                    'size' => 'Small, Medium',
                    'weight' => '20g, 250g',
                ],
            ],
            'Smartwatches' => [
                'products' => [
                    ['name' => 'Fitness Tracker', 'variants' => ['Basic', 'Advanced'], 'unit' => 'type', 'regular_price' => 8000],
                    ['name' => 'Premium Smartwatch', 'variants' => ['Standard', 'LTE'], 'unit' => 'type', 'regular_price' => 25000],
                    ['name' => 'Sports Smartwatch', 'variants' => ['Standard', 'Rugged'], 'unit' => 'type', 'regular_price' => 15000],
                    ['name' => 'Classic Smartwatch', 'variants' => ['Leather', 'Metal'], 'unit' => 'type', 'regular_price' => 20000],
                    ['name' => 'Budget Smartwatch', 'variants' => ['Basic', 'Color'], 'unit' => 'type', 'regular_price' => 5000],
                ],
                'attributes' => [
                    'color' => 'Black, Silver',
                    'size' => '40mm, 44mm',
                    'weight' => '30g, 50g',
                ],
            ],
            'Tablets' => [
                'products' => [
                    ['name' => 'Pro Tablet', 'variants' => ['128GB', '256GB'], 'unit' => 'GB', 'regular_price' => 60000],
                    ['name' => 'Budget Tablet', 'variants' => ['32GB', '64GB'], 'unit' => 'GB', 'regular_price' => 15000],
                    ['name' => 'Kids Tablet', 'variants' => ['32GB', '64GB'], 'unit' => 'GB', 'regular_price' => 10000],
                    ['name' => 'Gaming Tablet', 'variants' => ['128GB', '256GB'], 'unit' => 'GB', 'regular_price' => 50000],
                    ['name' => 'Drawing Tablet', 'variants' => ['64GB', '128GB'], 'unit' => 'GB', 'regular_price' => 40000],
                ],
                'attributes' => [
                    'color' => 'Grey, Gold',
                    'size' => '10inch, 12inch',
                    'weight' => '400g, 600g',
                ],
            ],
            'Televisions' => [
                'products' => [
                    ['name' => '4K Smart TV', 'variants' => ['43inch', '55inch'], 'unit' => 'inch', 'regular_price' => 50000],
                    ['name' => 'OLED TV', 'variants' => ['55inch', '65inch'], 'unit' => 'inch', 'regular_price' => 120000],
                    ['name' => 'QLED TV', 'variants' => ['50inch', '75inch'], 'unit' => 'inch', 'regular_price' => 90000],
                    ['name' => 'LED TV', 'variants' => ['32inch', '43inch'], 'unit' => 'inch', 'regular_price' => 25000],
                    ['name' => 'Curved TV', 'variants' => ['55inch', '65inch'], 'unit' => 'inch', 'regular_price' => 100000],
                ],
                'attributes' => [
                    'color' => 'Black',
                    'size' => 'Standard',
                    'weight' => '10kg, 20kg',
                ],
            ],
            'Cameras' => [
                'products' => [
                    ['name' => 'Mirrorless Camera', 'variants' => ['Basic', 'Pro'], 'unit' => 'type', 'regular_price' => 80000],
                    ['name' => 'DSLR Camera', 'variants' => ['Entry', 'Advanced'], 'unit' => 'type', 'regular_price' => 60000],
                    ['name' => 'Point-and-Shoot Camera', 'variants' => ['Basic', 'Zoom'], 'unit' => 'type', 'regular_price' => 20000],
                    ['name' => 'Action Camera', 'variants' => ['4K', '8K'], 'unit' => 'type', 'regular_price' => 30000],
                    ['name' => 'Instant Camera', 'variants' => ['Standard', 'Color'], 'unit' => 'type', 'regular_price' => 10000],
                ],
                'attributes' => [
                    'color' => 'Black, Silver',
                    'size' => 'Compact, Standard',
                    'weight' => '300g, 600g',
                ],
            ],
            'Speakers' => [
                'products' => [
                    ['name' => 'Bluetooth Speaker', 'variants' => ['Portable', 'Premium'], 'unit' => 'type', 'regular_price' => 5000],
                    ['name' => 'Home Theater Speaker', 'variants' => ['5.1', '7.1'], 'unit' => 'type', 'regular_price' => 30000],
                    ['name' => 'Soundbar', 'variants' => ['Basic', 'Dolby'], 'unit' => 'type', 'regular_price' => 15000],
                    ['name' => 'Outdoor Speaker', 'variants' => ['Standard', 'Waterproof'], 'unit' => 'type', 'regular_price' => 8000],
                    ['name' => 'Smart Speaker', 'variants' => ['Basic', 'Voice'], 'unit' => 'type', 'regular_price' => 10000],
                ],
                'attributes' => [
                    'color' => 'Black, White',
                    'size' => 'Small, Large',
                    'weight' => '500g, 5kg',
                ],
            ],
            'Gaming Consoles' => [
                'products' => [
                    ['name' => 'Gaming Console Pro', 'variants' => ['500GB', '1TB'], 'unit' => 'TB', 'regular_price' => 50000],
                    ['name' => 'Portable Console', 'variants' => ['Standard', 'Lite'], 'unit' => 'type', 'regular_price' => 25000],
                    ['name' => 'VR Console', 'variants' => ['Basic', 'Pro'], 'unit' => 'type', 'regular_price' => 40000],
                    ['name' => 'Retro Console', 'variants' => ['Classic', 'Mini'], 'unit' => 'type', 'regular_price' => 10000],
                    ['name' => 'Hybrid Console', 'variants' => ['Standard', 'OLED'], 'unit' => 'type', 'regular_price' => 35000],
                ],
                'attributes' => [
                    'color' => 'Black, White',
                    'size' => 'Standard',
                    'weight' => '2kg, 4kg',
                ],
            ],
            'Accessories' => [
                'products' => [
                    ['name' => 'USB-C Cable', 'variants' => ['1m', '2m'], 'unit' => 'meter', 'regular_price' => 1000],
                    ['name' => 'Wireless Charger', 'variants' => ['10W', '15W'], 'unit' => 'watt', 'regular_price' => 3000],
                    ['name' => 'Power Bank', 'variants' => ['10000mAh', '20000mAh'], 'unit' => 'mAh', 'regular_price' => 4000],
                    ['name' => 'Laptop Stand', 'variants' => ['Basic', 'Adjustable'], 'unit' => 'type', 'regular_price' => 2000],
                    ['name' => 'Screen Protector', 'variants' => ['Tempered', 'Matte'], 'unit' => 'type', 'regular_price' => 1500],
                ],
                'attributes' => [
                    'color' => 'Black, White',
                    'size' => 'Small, Medium',
                    'weight' => '50g, 200g',
                ],
            ],
        ];

        // Get all subcategories, brands, units, sizes, and colors
        $subcategories = SubCategory::all();
        $brands = Brand::all();
        $units = Unit::all();
        $sizes = Psize::all();
        $colors = Color::all();

        // Ensure there are subcategories available
        if ($subcategories->isEmpty()) {
            return;
        }

        // Loop through electronics products
        foreach ($electronicsProducts as $category => $data) {
            // Randomly assign a subcategory
            $subcategory = $subcategories->random();

            foreach ($data['products'] as $productData) {
                // Create a product
                $product = Product::create([
                    'name' => $productData['name'],
                    'category_id' => $subcategory->category_id,
                    'subcategory_id' => $subcategory->id,
                    'brand_id' => $brands->random()->id,
                    'unit' => $units->random()->id,
                    'description' => $faker->paragraph,
                    'status' => 'active',
                ]);

                // Create variants for the product
                foreach ($productData['variants'] as $index => $variant) {
                    $cost_price = $productData['regular_price'] * 0.7; // Example: 70% of regular price
                    $b2b_price = $cost_price + ($cost_price * 0.2); // 20% markup
                    $b2c_price = $b2b_price + ($b2b_price * 0.3); // 30% markup

                    $status = ($index === 0) ? 'default' : 'variant';

                    $variation = Variation::create([
                        'product_id' => $product->id,
                        'barcode' => $faker->unique()->ean13,
                        'cost_price' => $cost_price,
                        'b2b_price' => $b2b_price,
                        'b2c_price' => $b2c_price,
                        'size' => $sizes->random()->id,
                        'color' => $colors->random()->id,
                        'model_no' => $faker->bothify('MOD-####'),
                        'quality' => $faker->randomElement(['High', 'Medium', 'Low']),
                        'image' => $faker->imageUrl(640, 480, 'technics'),
                        'origin' => $faker->country,
                        'status' => $status,
                        'productStatus' => 'active',
                    ]);

                    // Create stock for the variation
                    Stock::create([
                        'branch_id' => 1, // Assuming branch_id 1 exists
                        'product_id' => $product->id,
                        'variation_id' => $variation->id,
                        'stock_quantity' => $faker->numberBetween(10, 100),
                        'stock_age' => $faker->randomElement(['New', '1 Month', '3 Months']),
                        'is_Current_stock' => true,
                        'manufacture_date' => $faker->dateTimeBetween('-1 year', 'now'),
                        'expiry_date' => $faker->dateTimeBetween('now', '+2 years'),
                        'status' => $faker->randomElement(['available', 'low_stock']),
                    ]);
                }
            }
        }
    }
}
