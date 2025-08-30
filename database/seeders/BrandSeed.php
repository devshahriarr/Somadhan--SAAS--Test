<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;

class BrandSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $brands = [
            [
                'id' => 1,
                'name' => 'Apple',
                'slug' => 'apple',
            ],
            [
                'id' => 2,
                'name' => 'Samsung',
                'slug' => 'samsung',
            ],
            [
                'id' => 3,
                'name' => 'Sony',
                'slug' => 'sony',
            ],
            [
                'id' => 4,
                'name' => 'LG',
                'slug' => 'lg',
            ],
            [
                'id' => 5,
                'name' => 'Dell',
                'slug' => 'dell',
            ],
            [
                'id' => 6,
                'name' => 'HP',
                'slug' => 'hp',
            ],
            [
                'id' => 7,
                'name' => 'Lenovo',
                'slug' => 'lenovo',
            ],
            [
                'id' => 8,
                'name' => 'Microsoft',
                'slug' => 'microsoft',
            ],
            [
                'id' => 9,
                'name' => 'NVIDIA',
                'slug' => 'nvidia',
            ],
            [
                'id' => 10,
                'name' => 'Intel',
                'slug' => 'intel',
            ],
            [
                'id' => 11,
                'name' => 'Asus',
                'slug' => 'asus',
            ],
            [
                'id' => 12,
                'name' => 'Acer',
                'slug' => 'acer',
            ],
            [
                'id' => 13,
                'name' => 'Panasonic',
                'slug' => 'panasonic',
            ],
            [
                'id' => 14,
                'name' => 'Canon',
                'slug' => 'canon',
            ],
            [
                'id' => 15,
                'name' => 'Nikon',
                'slug' => 'nikon',
            ],
            [
                'id' => 16,
                'name' => 'GoPro',
                'slug' => 'gopro',
            ],
            [
                'id' => 17,
                'name' => 'Bose',
                'slug' => 'bose',
            ],
            [
                'id' => 18,
                'name' => 'Beats by Dre',
                'slug' => 'beats-by-dre',
            ],
            [
                'id' => 19,
                'name' => 'JBL',
                'slug' => 'jbl',
            ],
            [
                'id' => 20,
                'name' => 'Philips',
                'slug' => 'philips',
            ],
            [
                'id' => 21,
                'name' => 'Xiaomi',
                'slug' => 'xiaomi',
            ],
            [
                'id' => 22,
                'name' => 'OnePlus',
                'slug' => 'oneplus',
            ],
            [
                'id' => 23,
                'name' => 'Huawei',
                'slug' => 'huawei',
            ],
            [
                'id' => 24,
                'name' => 'Oppo',
                'slug' => 'oppo',
            ],
            [
                'id' => 25,
                'name' => 'Vivo',
                'slug' => 'vivo',
            ],
            [
                'id' => 26,
                'name' => 'Razer',
                'slug' => 'razer',
            ],
            [
                'id' => 27,
                'name' => 'Corsair',
                'slug' => 'corsair',
            ],
            [
                'id' => 28,
                'name' => 'Logitech',
                'slug' => 'logitech',
            ],
            [
                'id' => 29,
                'name' => 'Western Digital',
                'slug' => 'western-digital',
            ],
            [
                'id' => 30,
                'name' => 'Seagate',
                'slug' => 'seagate',
            ],
            [
                'id' => 31,
                'name' => 'TP-Link',
                'slug' => 'tp-link',
            ],
            [
                'id' => 32,
                'name' => 'Netgear',
                'slug' => 'netgear',
            ],
            [
                'id' => 33,
                'name' => 'Epson',
                'slug' => 'epson',
            ],
            [
                'id' => 34,
                'name' => 'Brother',
                'slug' => 'brother',
            ],
            [
                'id' => 35,
                'name' => 'Roku',
                'slug' => 'roku',
            ],
            [
                'id' => 36,
                'name' => 'TCL',
                'slug' => 'tcl',
            ],
            [
                'id' => 37,
                'name' => 'Sharp',
                'slug' => 'sharp',
            ],
            [
                'id' => 38,
                'name' => 'Sennheiser',
                'slug' => 'sennheiser',
            ],
            [
                'id' => 39,
                'name' => 'Bang & Olufsen',
                'slug' => 'bang-olufsen',
            ],
            [
                'id' => 40,
                'name' => 'Sony Ericsson',
                'slug' => 'sony-ericsson',
            ],
            [
                'id' => 41,
                'name' => 'Amazon',
                'slug' => 'amazon',
            ],
            [
                'id' => 42,
                'name' => 'Google',
                'slug' => 'google',
            ],
            [
                'id' => 43,
                'name' => 'Ring',
                'slug' => 'ring',
            ],
            [
                'id' => 44,
                'name' => 'Dyson',
                'slug' => 'dyson',
            ],
            [
                'id' => 45,
                'name' => 'iRobot',
                'slug' => 'irobot',
            ],
            [
                'id' => 46,
                'name' => 'Bowers & Wilkins',
                'slug' => 'bowers-wilkins',
            ],
            [
                'id' => 47,
                'name' => 'Marantz',
                'slug' => 'marantz',
            ],
            [
                'id' => 48,
                'name' => 'Denon',
                'slug' => 'denon',
            ],
            [
                'id' => 49,
                'name' => 'Harman Kardon',
                'slug' => 'harman-kardon',
            ],
            [
                'id' => 50,
                'name' => 'Samsung SmartThings',
                'slug' => 'samsung-smartthings',
            ],
        ];

        foreach ($brands as $brands) {
            Brand::create($brands);
        }
    }
}
