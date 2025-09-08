<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Category;
use App\Models\PosSetting;
use Illuminate\Database\Seeder;
use Spatie\Multitenancy\Models\Tenant;

class TenantSeeder extends Seeder
{
    public function run()
    {
        $tenant = Tenant::current();
        if (!$tenant) {
            return;
        }

        // Create default roles
        Role::firstOrCreate([
            'name' => 'admin',
            'tenant_id' => $tenant->id,
            'guard_name' => 'web',
        ]);

        Role::firstOrCreate([
            'name' => 'staff',
            'tenant_id' => $tenant->id,
            'guard_name' => 'web',
        ]);

        // Create default category
        Category::create([
            'tenant_id' => $tenant->id,
            'name' => 'Default Category',
        ]);

        // Create default POS settings
        // PosSetting::create([
        //     'tenant_id' => $tenant->id,
        //     'key' => 'default_settings',
        //     'value' => json_encode(['currency' => 'USD']),
        // ]);
    }
}