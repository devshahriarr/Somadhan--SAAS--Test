<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseItem extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function variant()
    {
        return $this->belongsTo(Variation::class, 'variant_id', 'id');
    }

    public function Purchas()
    {
        return $this->belongsTo(Purchase::class, 'purchase_id', 'id');
    }
}
