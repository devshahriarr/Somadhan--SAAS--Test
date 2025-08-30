<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Variation extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function stocks()
    {
        return $this->hasMany(Stock::class, 'variation_id');
    }

    public function variationSize() // Fix the spelling!
    {
        return $this->belongsTo(Psize::class, 'size', 'id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function stockQuantity()
    {
        return $this->hasOne(Stock::class, 'variation_id');
    }

    public function saleItem()
    {
        return $this->hasMany(SaleItem::class, 'variant_id');
    }

    public function colorName()
    {
        return $this->belongsTo(Color::class, 'color', 'id');
    }
}
