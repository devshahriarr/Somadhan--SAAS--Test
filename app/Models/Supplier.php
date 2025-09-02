<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Multitenancy\Models\Concerns\UsesTenantModel;
class Supplier extends Model
{
    use HasFactory;
    use UsesTenantModel;

    protected $guarded = [];

    public function purchase()
    {
        return $this->hasMany(Purchase::class, 'supplier_id', 'id');
    }

    // protected static function boot()
    // {
    //     parent::boot();

    //     // Calculate balance before saving the transaction
    //     static::saving(function ($supplier) {
    //         // Assuming you want to fetch the last balance first
    //         $lastTransaction = self::find($supplier->id);
    //         $lastBalance = $lastTransaction ? $lastTransaction->wallet_balance : 0;

    //         // Update the current balance
    //         $supplier->wallet_balance = $lastBalance + ($supplier->total_payable + $supplier->total_receivable);
    //     });
    // }
}
