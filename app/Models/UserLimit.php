<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Multitenancy\Models\Concerns\UsesTenantModel;

class UserLimit extends Model
{
    use UsesTenantModel;
    use HasFactory;

    protected $fillable = ['tenant_id', 'company_id', 'user_limit'];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
