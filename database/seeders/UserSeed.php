<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'id' => 1,
                'company_id' => 1,
                'name' => 'Super Admin',
                'email' => 'superadmin@gmail.com',
                'phone' => '12345656',
                'branch_id' => 1,
                'password' => Hash::make('111'),
                'role' => 'superadmin',
            ],
            [
                'id' => 2,
                'company_id' => 1,
                'name' => 'Admin',
                'email' => 'admin@gmail.com',
                'phone' => '1234567838',
                'branch_id' => 1,
                'password' => Hash::make('123'),
                'role' => 'admin',
            ],
            [
                'id' => 3,
                'company_id' => 1,
                'name' => 'Demo',
                'email' => 'demo@gmail.com',
                'phone' => '1234538',
                'branch_id' => 1,
                'password' => Hash::make('12345678'),
                'role' => 'demo',
            ],
            [
                'id' => 4,
                'company_id' => 1,
                'name' => 'TecAdmin',
                'email' => 'tecadmin@gmail.com',
                'phone' => '1234538',
                'branch_id' => 1,
                'password' => Hash::make('eil12345'),
                'role' => 'superadmin',
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
