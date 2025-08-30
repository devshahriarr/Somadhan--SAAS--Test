<?php

namespace Database\Seeders;

use App\Models\NewUser;
use Illuminate\Database\Seeder;

class DummyUser extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        NewUser::factory(10000)->create();
    }
}
