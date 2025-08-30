<?php

namespace App\Models\LoanManagement;

use App\Models\Bank;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Multitenancy\Models\Concerns\UsesTenantModel;

class Loan extends Model
{
    use UsesTenantModel;
    use HasFactory;

    protected $guarded = [];

    public function bankAccounts()
    {
        return $this->belongsTo(Bank::class);
    } //
}
