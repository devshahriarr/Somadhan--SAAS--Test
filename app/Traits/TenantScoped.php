<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Spatie\Multitenancy\Models\Tenant;

trait TenantScoped
{
    protected static function bootTenantScoped()
    {
        // static::addGlobalScope('tenant', function (Builder $builder) {
        //     if ($tenant = Tenant::current()) {
        //         $builder->where($this->getTable() . '.tenant_id', $tenant->id);
        //     }
        // });

        static::addGlobalScope('tenant', function (Builder $builder) {
            if ($tenant = Tenant::current()) {
                // Use static::getTable() or (new static)->getTable() to get table name
                $builder->where((new static)->getTable() . '.tenant_id', $tenant->id);
            }
        });
    }
}