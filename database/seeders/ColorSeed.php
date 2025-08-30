<?php

namespace Database\Seeders;

use App\Models\Color;
use Illuminate\Database\Seeder;

class ColorSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $colors = [
            ['name' => 'Red'],
            ['name' => 'Blue'],
            ['name' => 'Green'],
            ['name' => 'Yellow'],
            ['name' => 'Purple'],
            ['name' => 'Orange'],
            ['name' => 'Pink'],
            ['name' => 'Black'],
            ['name' => 'White'],
            ['name' => 'Gray'],
            ['name' => 'Brown'],
            ['name' => 'Cyan'],
            ['name' => 'Magenta'],
            ['name' => 'Lime'],
            ['name' => 'Teal'],
            ['name' => 'Indigo'],
            ['name' => 'Violet'],
            ['name' => 'Maroon'],
            ['name' => 'Olive'],
            ['name' => 'Turquoise'],
        ];

        foreach ($colors as $color) {
            Color::create($color);
        }
    }
}
