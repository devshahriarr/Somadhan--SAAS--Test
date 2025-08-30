<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttributeManage extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function extra_field()
    {
        return $this->belongsTo(Attribute::class, 'extra_field_id', 'id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}
