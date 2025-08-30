<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Multitenancy\Models\Concerns\UsesTenantModel;

class AccountTransaction extends Model
{
    use UsesTenantModel;
    use HasFactory;

    protected $guarded = [];

    public function bank()
    {
        return $this->belongsTo(Bank::class, 'account_id', 'id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'processed_by', 'id');
    }

    public function sale()
    {
        return $this->belongsTo(Sale::class, 'reference_id', 'id');
    }

    public function purchase()
    {
        return $this->belongsTo(Purchase::class, 'reference_id', 'id');
    }

    public function bankToBankTransfer()
    {
        return $this->belongsTo(BankToBankTransfer::class, 'reference_id', 'id');
    }
}
