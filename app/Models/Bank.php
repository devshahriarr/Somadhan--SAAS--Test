<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Multitenancy\Models\Concerns\UsesTenantModel;

class Bank extends Model
{
    use UsesTenantModel;
    use HasFactory;

    protected $guarded = [];

    public function accountTransaction()
    {
        return $this->hasMany(AccountTransaction::class, 'account_id', 'id');
    }
}
