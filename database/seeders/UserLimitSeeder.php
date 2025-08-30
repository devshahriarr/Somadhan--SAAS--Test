<?php

namespace Database\Seeders;

use App\Models\UserLimit;
use Illuminate\Database\Seeder;

class UserLimitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        UserLimit::create([
            'company_id' => 1,
            'user_limit' => 5,
        ]);
    }
}
