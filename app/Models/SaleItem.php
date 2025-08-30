<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleItem extends Model
{
    use HasFactory;
    // protected $guarded = [];

    protected $fillable = [
        'sale_id', 'product_id', 'variant_id', 'rate', 'qty', 'created_at', 'updated_at',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function variant()
    {
        return $this->belongsTo(Variation::class, 'variant_id', 'id');
    }

    public function saleId()
    {
        return $this->belongsTo(Sale::class, 'sale_id', 'id');
    }
}
