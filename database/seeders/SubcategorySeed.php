<?php

namespace Database\Seeders;

use App\Models\SubCategory;
use Illuminate\Database\Seeder;

class SubcategorySeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subcategories = [
            // Category 1: Mobile Phones
            [
                'id' => 1,
                'category_id' => 1,
                'name' => 'Gaming Phones',
                'slug' => 'gaming-phones',
            ],
            [
                'id' => 2,
                'category_id' => 1,
                'name' => 'Feature Phones',
                'slug' => 'feature-phones',
            ],
            [
                'id' => 3,
                'category_id' => 1,
                'name' => 'Budget Phones',
                'slug' => 'budget-phones',
            ],
            [
                'id' => 4,
                'category_id' => 1,
                'name' => 'Flagship Phones',
                'slug' => 'flagship-phones',
            ],
            [
                'id' => 5,
                'category_id' => 1,
                'name' => 'Refurbished Phones',
                'slug' => 'refurbished-phones',
            ],

            // Category 2: Home Appliances
            [
                'id' => 6,
                'category_id' => 2,
                'name' => 'Refrigerators',
                'slug' => 'refrigerators',
            ],
            [
                'id' => 7,
                'category_id' => 2,
                'name' => 'Washing Machines',
                'slug' => 'washing-machines',
            ],
            [
                'id' => 8,
                'category_id' => 2,
                'name' => 'Air Conditioners',
                'slug' => 'air-conditioners',
            ],
            [
                'id' => 9,
                'category_id' => 2,
                'name' => 'Microwaves',
                'slug' => 'microwaves',
            ],
            [
                'id' => 10,
                'category_id' => 2,
                'name' => 'Vacuum Cleaners',
                'slug' => 'vacuum-cleaners',
            ],

            // Category 3: Laptops
            [
                'id' => 11,
                'category_id' => 3,
                'name' => 'Gaming Laptops',
                'slug' => 'gaming-laptops',
            ],
            [
                'id' => 12,
                'category_id' => 3,
                'name' => 'Ultrabooks',
                'slug' => 'ultrabooks',
            ],
            [
                'id' => 13,
                'category_id' => 3,
                'name' => 'Business Laptops',
                'slug' => 'business-laptops',
            ],
            [
                'id' => 14,
                'category_id' => 3,
                'name' => 'Chromebooks',
                'slug' => 'chromebooks',
            ],
            [
                'id' => 15,
                'category_id' => 3,
                'name' => '2-in-1 Laptops',
                'slug' => '2-in-1-laptops',
            ],

            // Category 4: Televisions
            [
                'id' => 16,
                'category_id' => 4,
                'name' => 'Smart TVs',
                'slug' => 'smart-tvs',
            ],
            [
                'id' => 17,
                'category_id' => 4,
                'name' => '4K TVs',
                'slug' => '4k-tvs',
            ],
            [
                'id' => 18,
                'category_id' => 4,
                'name' => 'OLED TVs',
                'slug' => 'oled-tvs',
            ],
            [
                'id' => 19,
                'category_id' => 4,
                'name' => 'Curved TVs',
                'slug' => 'curved-tvs',
            ],
            [
                'id' => 20,
                'category_id' => 4,
                'name' => 'LED TVs',
                'slug' => 'led-tvs',
            ],

            // Category 5: Cameras
            [
                'id' => 21,
                'category_id' => 5,
                'name' => 'DSLR Cameras',
                'slug' => 'dslr-cameras',
            ],
            [
                'id' => 22,
                'category_id' => 5,
                'name' => 'Mirrorless Cameras',
                'slug' => 'mirrorless-cameras',
            ],
            [
                'id' => 23,
                'category_id' => 5,
                'name' => 'Action Cameras',
                'slug' => 'action-cameras',
            ],
            [
                'id' => 24,
                'category_id' => 5,
                'name' => 'Point-and-Shoot Cameras',
                'slug' => 'point-and-shoot-cameras',
            ],
            [
                'id' => 25,
                'category_id' => 5,
                'name' => 'Instant Cameras',
                'slug' => 'instant-cameras',
            ],

            // Category 6: Headphones
            [
                'id' => 26,
                'category_id' => 6,
                'name' => 'Wireless Headphones',
                'slug' => 'wireless-headphones',
            ],
            [
                'id' => 27,
                'category_id' => 6,
                'name' => 'Noise-Cancelling Headphones',
                'slug' => 'noise-cancelling-headphones',
            ],
            [
                'id' => 28,
                'category_id' => 6,
                'name' => 'Earbuds',
                'slug' => 'earbuds',
            ],
            [
                'id' => 29,
                'category_id' => 6,
                'name' => 'Over-Ear Headphones',
                'slug' => 'over-ear-headphones',
            ],
            [
                'id' => 30,
                'category_id' => 6,
                'name' => 'Sports Headphones',
                'slug' => 'sports-headphones',
            ],

            // Category 7: Smart Watches
            [
                'id' => 31,
                'category_id' => 7,
                'name' => 'Fitness Trackers',
                'slug' => 'fitness-trackers',
            ],
            [
                'id' => 32,
                'category_id' => 7,
                'name' => 'Luxury Smart Watches',
                'slug' => 'luxury-smart-watches',
            ],
            [
                'id' => 33,
                'category_id' => 7,
                'name' => 'Kids Smart Watches',
                'slug' => 'kids-smart-watches',
            ],
            [
                'id' => 34,
                'category_id' => 7,
                'name' => 'Rugged Smart Watches',
                'slug' => 'rugged-smart-watches',
            ],
            [
                'id' => 35,
                'category_id' => 7,
                'name' => 'Hybrid Smart Watches',
                'slug' => 'hybrid-smart-watches',
            ],

            // Category 8: Gaming Consoles
            [
                'id' => 36,
                'category_id' => 8,
                'name' => 'PlayStation',
                'slug' => 'playstation',
            ],
            [
                'id' => 37,
                'category_id' => 8,
                'name' => 'Xbox',
                'slug' => 'xbox',
            ],
            [
                'id' => 38,
                'category_id' => 8,
                'name' => 'Nintendo Switch',
                'slug' => 'nintendo-switch',
            ],
            [
                'id' => 39,
                'category_id' => 8,
                'name' => 'Gaming PCs',
                'slug' => 'gaming-pcs',
            ],
            [
                'id' => 40,
                'category_id' => 8,
                'name' => 'VR Gaming',
                'slug' => 'vr-gaming',
            ],

            // Category 9: Tablets
            [
                'id' => 41,
                'category_id' => 9,
                'name' => 'Android Tablets',
                'slug' => 'android-tablets',
            ],
            [
                'id' => 42,
                'category_id' => 9,
                'name' => 'iPad',
                'slug' => 'ipad',
            ],
            [
                'id' => 43,
                'category_id' => 9,
                'name' => 'Windows Tablets',
                'slug' => 'windows-tablets',
            ],
            [
                'id' => 44,
                'category_id' => 9,
                'name' => 'Kids Tablets',
                'slug' => 'kids-tablets',
            ],
            [
                'id' => 45,
                'category_id' => 9,
                'name' => 'E-Readers',
                'slug' => 'e-readers',
            ],

            // Category 10: Printers
            [
                'id' => 46,
                'category_id' => 10,
                'name' => 'Inkjet Printers',
                'slug' => 'inkjet-printers',
            ],
            [
                'id' => 47,
                'category_id' => 10,
                'name' => 'Laser Printers',
                'slug' => 'laser-printers',
            ],
            [
                'id' => 48,
                'category_id' => 10,
                'name' => 'Photo Printers',
                'slug' => 'photo-printers',
            ],
            [
                'id' => 49,
                'category_id' => 10,
                'name' => 'All-in-One Printers',
                'slug' => 'all-in-one-printers',
            ],
            [
                'id' => 50,
                'category_id' => 10,
                'name' => '3D Printers',
                'slug' => '3d-printers',
            ],

            // Category 11: Monitors
            [
                'id' => 51,
                'category_id' => 11,
                'name' => 'Gaming Monitors',
                'slug' => 'gaming-monitors',
            ],
            [
                'id' => 52,
                'category_id' => 11,
                'name' => 'Ultrawide Monitors',
                'slug' => 'ultrawide-monitors',
            ],
            [
                'id' => 53,
                'category_id' => 11,
                'name' => '4K Monitors',
                'slug' => '4k-monitors',
            ],
            [
                'id' => 54,
                'category_id' => 11,
                'name' => 'Curved Monitors',
                'slug' => 'curved-monitors',
            ],
            [
                'id' => 55,
                'category_id' => 11,
                'name' => 'Touchscreen Monitors',
                'slug' => 'touchscreen-monitors',
            ],

            // Category 12: Networking Devices
            [
                'id' => 56,
                'category_id' => 12,
                'name' => 'Routers',
                'slug' => 'routers',
            ],
            [
                'id' => 57,
                'category_id' => 12,
                'name' => 'Modems',
                'slug' => 'modems',
            ],
            [
                'id' => 58,
                'category_id' => 12,
                'name' => 'Switches',
                'slug' => 'switches',
            ],
            [
                'id' => 59,
                'category_id' => 12,
                'name' => 'Network Adapters',
                'slug' => 'network-adapters',
            ],
            [
                'id' => 60,
                'category_id' => 12,
                'name' => 'Range Extenders',
                'slug' => 'range-extenders',
            ],
        ];

        foreach ($subcategories as $subcategory) {
            SubCategory::create($subcategory);
        }
    }
}
