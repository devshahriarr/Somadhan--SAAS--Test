<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockAdjustmentItems extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function variation()
    {
        return $this->belongsTo(Variation::class, 'variation_id', 'id');
    }

    public function stockAdjustment()
    {
        return $this->hasMany(StockAdjustment::class);
    }
}
