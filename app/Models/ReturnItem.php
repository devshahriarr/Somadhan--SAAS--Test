<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnItem extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function return()
    {
        return $this->belongsTo(Returns::class);
    }

    public function variant()
    {
        return $this->belongsTo(Variation::class, 'variant_id', 'id');
    }
}
