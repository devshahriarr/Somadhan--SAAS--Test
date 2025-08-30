<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Multitenancy\Models\Concerns\UsesTenantModel;

class Product extends Model
{
    use UsesTenantModel;
    use HasFactory;

    protected $guarded = [];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function variation()
    {
        return $this->hasOne(Variation::class);
    }

    public function subcategory()
    {
        return $this->belongsTo(SubCategory::class, 'subcategory_id', 'id');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id', 'id');
    }

    public function productUnit()
    {
        return $this->belongsTo(Unit::class, 'unit', 'id');
    }

    public function damage()
    {
        return $this->hasMany(Damage::class);
    }

    public function purchaseItems()
    {
        return $this->hasMany(PurchaseItem::class, 'product_id');
    }

    public function stockQuantity()
    {
        return $this->hasMany(Stock::class, 'product_id');
    }

    public function variations()
    {
        return $this->hasMany(Variation::class, 'product_id');
    }

    public function defaultVariations()
    {
        return $this->hasOne(Variation::class)->where('status', 'default');
    }

    public function defaultVariationsEdit()
    {
        return $this->hasOne(Variation::class)->where('status', 'default');
    }

    public function saleItem()
    {
        return $this->hasMany(SaleItem::class, 'product_id');
    }

    public function product_extra_field_manage()
    {
        return $this->hasMany(AttributeManage::class, 'product_id', 'id');
    }
}
