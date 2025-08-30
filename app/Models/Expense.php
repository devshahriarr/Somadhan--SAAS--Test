<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Multitenancy\Models\Concerns\UsesTenantModel;

class Expense extends Model
{
    use UsesTenantModel;
    use HasFactory;

    protected $guarded = [];

    public function bank()
    {
        return $this->belongsTo(Bank::class, 'bank_account_id', 'id');
    }

    //
    public function expenseCat()
    {
        return $this->belongsTo(ExpenseCategory::class, 'expense_category_id', 'id');
    } //

}
