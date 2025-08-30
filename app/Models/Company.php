<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Multitenancy\Models\Concerns\UsesTenantModel;

class Company extends Model
{
    use UsesTenantModel;
    use HasFactory;

    protected $fillable = ['tenant_id', 'name'];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function userLimit()
    {
        return $this->hasOne(UserLimit::class);
    }
}
