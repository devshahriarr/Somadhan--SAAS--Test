<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Multitenancy\Models\Concerns\UsesTenantModel;

class Device extends Model
{
    use UsesTenantModel;
    use HasFactory;

    protected $fillable = ['tenant_id', 'user_id', 'device_identifier', 'is_primary', 'mobile_permission'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
