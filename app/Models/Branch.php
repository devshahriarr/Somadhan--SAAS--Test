<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Multitenancy\Models\Concerns\UsesTenantModel;
use App\Traits\TenantScoped;
class Branch extends Model
{
    use UsesTenantModel;
    use HasFactory;
    use TenantScoped;

    // protected $guarded = [];
    protected $fillable = ['tenant_id', 'name', 'address', 'phone', 'email', 'logo', 'manager_id'];

    protected static function boot()
    {
        parent::boot();
        static::bootTenantScoped(); // Apply tenant scope
    }

    public function users()
    {
        return $this->hasMany(User::class, 'branch_id');
    }

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }
    public function stocks()
    {
        return $this->hasMany(Stock::class, 'branch_id');
    }
}
