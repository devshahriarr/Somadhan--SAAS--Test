<?php

namespace Database\Seeders;

use App\Models\Unit;
use Illuminate\Database\Seeder;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $units = [
            ['id' => 1, 'name' => 'Piece'],
            ['id' => 2, 'name' => 'Packet'],
            ['id' => 3, 'name' => 'Box'],
            ['id' => 4, 'name' => 'Carton'],
            ['id' => 5, 'name' => 'Kilogram'],
            ['id' => 6, 'name' => 'Gram'],
            ['id' => 7, 'name' => 'Pound'],
            ['id' => 8, 'name' => 'Ounce'],
            ['id' => 9, 'name' => 'Liter'],
            ['id' => 10, 'name' => 'Milliliter'],
            ['id' => 11, 'name' => 'Gallon'],
            ['id' => 12, 'name' => 'Barrel'],
            ['id' => 13, 'name' => 'Dozen'],
            ['id' => 14, 'name' => 'Pair'],
            ['id' => 15, 'name' => 'Set'],
            ['id' => 16, 'name' => 'Roll'],
            ['id' => 17, 'name' => 'Bundle'],
            ['id' => 18, 'name' => 'Sack'],
            ['id' => 19, 'name' => 'Can'],
            ['id' => 20, 'name' => 'Bottle'],
            ['id' => 21, 'name' => 'Tube'],
            ['id' => 22, 'name' => 'Sheet'],
            ['id' => 23, 'name' => 'Square Meter'],
            ['id' => 24, 'name' => 'Cubic Meter'],
            ['id' => 25, 'name' => 'Yard'],
            ['id' => 26, 'name' => 'Foot'],
            ['id' => 27, 'name' => 'Inch'],
        ];

        foreach ($units as $unit) {
            Unit::create($unit);
        }
    }
}
