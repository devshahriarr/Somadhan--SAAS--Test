<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Multitenancy\Models\Concerns\UsesTenantModel;

class BankToBankTransfer extends Model
{
    use UsesTenantModel;
    use HasFactory;

    protected $guarded = [];

    public function fromBank()
    {
        return $this->belongsTo(Bank::class, 'from', 'id');
    }

    public function toBank()
    {
        return $this->belongsTo(Bank::class, 'to', 'id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
}
