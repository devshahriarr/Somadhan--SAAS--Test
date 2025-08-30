<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'id' => 1,
                'name' => 'Mobile Phones',
                'slug' => 'mobile-phones',
            ],
            [
                'id' => 2,
                'name' => 'Home Appliances',
                'slug' => 'home-appliances',
            ],
            [
                'id' => 3,
                'name' => 'Laptops',
                'slug' => 'laptops',
            ],
            [
                'id' => 4,
                'name' => 'Televisions',
                'slug' => 'televisions',
            ],
            [
                'id' => 5,
                'name' => 'Cameras',
                'slug' => 'cameras',
            ],
            [
                'id' => 6,
                'name' => 'Headphones',
                'slug' => 'headphones',
            ],
            [
                'id' => 7,
                'name' => 'Smart Watches',
                'slug' => 'smart-watches',
            ],
            [
                'id' => 8,
                'name' => 'Gaming Consoles',
                'slug' => 'gaming-consoles',
            ],
            [
                'id' => 9,
                'name' => 'Tablets',
                'slug' => 'tablets',
            ],
            [
                'id' => 10,
                'name' => 'Printers',
                'slug' => 'printers',
            ],
            [
                'id' => 11,
                'name' => 'Monitors',
                'slug' => 'monitors',
            ],
            [
                'id' => 12,
                'name' => 'Networking Devices',
                'slug' => 'networking-devices',
            ],
        ];

        foreach ($categories as $productCategory) {
            Category::create($productCategory);
        }
    }
}
