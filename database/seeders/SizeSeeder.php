<?php

namespace Database\Seeders;

use App\Models\Psize;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class SizeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sizes = [
            // Category 1: Mobile Phones (Sizes based on storage or screen size)
            [
                'id' => 1,
                'category_id' => 1,
                'size' => '64GB',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => 2,
                'category_id' => 1,
                'size' => '128GB',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => 3,
                'category_id' => 1,
                'size' => '256GB',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => 4,
                'category_id' => 1,
                'size' => '512GB',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => 5,
                'category_id' => 1,
                'size' => '1TB',
                'created_at' => Carbon::now(),
            ],

            // Category 2: Home Appliances (Sizes based on capacity or dimensions)
            [
                'id' => 6,
                'category_id' => 2,
                'size' => 'Small',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => 7,
                'category_id' => 2,
                'size' => 'Medium',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => 8,
                'category_id' => 2,
                'size' => 'Large',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => 9,
                'category_id' => 2,
                'size' => 'Extra Large',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => 10,
                'category_id' => 2,
                'size' => 'Compact',
                'created_at' => Carbon::now(),
            ],

            // Category 3: Laptops (Sizes based on screen size or storage)
            [
                'id' => 11,
                'category_id' => 3,
                'size' => '13 inch',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => 12,
                'category_id' => 3,
                'size' => '15 inch',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => 13,
                'category_id' => 3,
                'size' => '17 inch',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => 14,
                'category_id' => 3,
                'size' => '256GB SSD',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => 15,
                'category_id' => 3,
                'size' => '512GB SSD',
                'created_at' => Carbon::now(),
            ],

            // Category 4: Televisions (Sizes based on screen size)
            [
                'id' => 16,
                'category_id' => 4,
                'size' => '32 inch',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => 17,
                'category_id' => 4,
                'size' => '40 inch',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => 18,
                'category_id' => 4,
                'size' => '50 inch',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => 19,
                'category_id' => 4,
                'size' => '55 inch',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => 20,
                'category_id' => 4,
                'size' => '65 inch',
                'created_at' => Carbon::now(),
            ],

            // Category 5: Cameras (Sizes based on sensor or lens)
            [
                'id' => 21,
                'category_id' => 5,
                'size' => 'APS-C',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => 22,
                'category_id' => 5,
                'size' => 'Full Frame',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => 23,
                'category_id' => 5,
                'size' => 'Micro Four Thirds',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => 24,
                'category_id' => 5,
                'size' => 'Medium Format',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => 25,
                'category_id' => 5,
                'size' => '1 inch',
                'created_at' => Carbon::now(),
            ],

            // Category 6: Headphones (Sizes based on fit or type)
            [
                'id' => 26,
                'category_id' => 6,
                'size' => 'Small',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => 27,
                'category_id' => 6,
                'size' => 'Medium',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => 28,
                'category_id' => 6,
                'size' => 'Large',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => 29,
                'category_id' => 6,
                'size' => 'Over-Ear',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => 30,
                'category_id' => 6,
                'size' => 'In-Ear',
                'created_at' => Carbon::now(),
            ],

            // Category 7: Smart Watches (Sizes based on strap or case size)
            [
                'id' => 31,
                'category_id' => 7,
                'size' => '38mm',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => 32,
                'category_id' => 7,
                'size' => '40mm',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => 33,
                'category_id' => 7,
                'size' => '42mm',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => 34,
                'category_id' => 7,
                'size' => '44mm',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => 35,
                'category_id' => 7,
                'size' => '46mm',
                'created_at' => Carbon::now(),
            ],

            // Category 8: Gaming Consoles (Sizes based on storage or edition)
            [
                'id' => 36,
                'category_id' => 8,
                'size' => '500GB',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => 37,
                'category_id' => 8,
                'size' => '1TB',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => 38,
                'category_id' => 8,
                'size' => '2TB',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => 39,
                'category_id' => 8,
                'size' => 'Digital Edition',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => 40,
                'category_id' => 8,
                'size' => 'Disc Edition',
                'created_at' => Carbon::now(),
            ],

            // Category 9: Tablets (Sizes based on screen size or storage)
            [
                'id' => 41,
                'category_id' => 9,
                'size' => '8 inch',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => 42,
                'category_id' => 9,
                'size' => '10 inch',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => 43,
                'category_id' => 9,
                'size' => '12 inch',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => 44,
                'category_id' => 9,
                'size' => '64GB',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => 45,
                'category_id' => 9,
                'size' => '128GB',
                'created_at' => Carbon::now(),
            ],

            // Category 10: Printers (Sizes based on paper size or type)
            [
                'id' => 46,
                'category_id' => 10,
                'size' => 'A4',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => 47,
                'category_id' => 10,
                'size' => 'A3',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => 48,
                'category_id' => 10,
                'size' => 'Letter',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => 49,
                'category_id' => 10,
                'size' => 'Legal',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => 50,
                'category_id' => 10,
                'size' => 'Photo',
                'created_at' => Carbon::now(),
            ],

            // Category 11: Monitors (Sizes based on screen size or resolution)
            [
                'id' => 51,
                'category_id' => 11,
                'size' => '24 inch',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => 52,
                'category_id' => 11,
                'size' => '27 inch',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => 53,
                'category_id' => 11,
                'size' => '32 inch',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => 54,
                'category_id' => 11,
                'size' => '4K',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => 55,
                'category_id' => 11,
                'size' => 'Ultrawide',
                'created_at' => Carbon::now(),
            ],

            // Category 12: Networking Devices (Sizes based on ports or speed)
            [
                'id' => 56,
                'category_id' => 12,
                'size' => '4 Ports',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => 57,
                'category_id' => 12,
                'size' => '8 Ports',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => 58,
                'category_id' => 12,
                'size' => '16 Ports',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => 59,
                'category_id' => 12,
                'size' => 'Gigabit',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => 60,
                'category_id' => 12,
                'size' => '10 Gigabit',
                'created_at' => Carbon::now(),
            ],
        ];

        foreach ($sizes as $size) {
            Psize::create($size);
        }
    }
}
