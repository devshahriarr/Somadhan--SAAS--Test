<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            BranchSeed::class,
            CompanySeeder::class,
            UserLimitSeeder::class,
            UserSeed::class,
            SettingSeed::class,
            PermissionSeeder::class,
            RoleSeeder::class,
            RoleHasPermission::class,
            ModelHasRolesSeeder::class,
            BankSeeder::class,
            CategorySeeder::class,
            SizeSeeder::class,
            UnitSeeder::class,
            ExpenseCategorySeeder::class,
            SubcategorySeed::class,
            BrandSeed::class,
            ColorSeed::class,
            ProductSeed::class,
            StockSeeder::class,
            AccountTransactionSeed::class,
            CustomerSeeder::class,
        ]);
    }
}
