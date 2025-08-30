<?php

namespace Database\Seeders;

use App\Models\Branch;
use Illuminate\Database\Seeder;

class BranchSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Branch::create([
            'name' => 'Eclipse Electro POS',
            'address' => 'House 41, Road 6, Block E, Banasree, Rampura, Dhaka, Bangladesh',
            'email' => 'eclipse.electro@gmail.com',
            'phone' => '01917344267',
        ]);
    }
}
