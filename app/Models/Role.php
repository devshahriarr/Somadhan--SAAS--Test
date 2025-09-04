<?php

namespace App\Models;

use Spatie\Permission\Models\Role as SpatieRole;
use Spatie\Multitenancy\Models\Concerns\UsesTenantModel;
use App\Traits\TenantScoped;

class Role extends SpatieRole
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
