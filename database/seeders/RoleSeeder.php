<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            ['id' => 1, 'name' => 'Super Admin', 'guard_name' => 'web', 'created_at' => Carbon::now()],
            ['id' => 2, 'name' => 'Admin', 'guard_name' => 'web', 'created_at' => Carbon::now()],
            ['id' => 3, 'name' => 'Demo', 'guard_name' => 'web', 'created_at' => Carbon::now()],
            ['id' => 4, 'name' => 'TecAdmin', 'guard_name' => 'web', 'created_at' => Carbon::now()],
            ['id' => 5, 'name' => 'salesman', 'guard_name' => 'web', 'created_at' => Carbon::now()],
            // add more Role as needed//
        ];
        // Insert permissions into the database
        DB::table('roles')->insert($roles);
    }
}
