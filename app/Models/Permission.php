<?php

namespace App\Models;

use Spatie\Permission\Models\Permission as SpatiePermission;
use Spatie\Multitenancy\Models\Concerns\UsesTenantModel;
use App\Traits\TenantScoped;

class Permission extends SpatiePermission
{
    use UsesTenantModel;
    use TenantScoped;

    protected $fillable = ['name', 'tenant_id', 'guard_name'];

    protected static function boot()
    {
        parent::boot();
        static::bootTenantScoped();
    }
}
