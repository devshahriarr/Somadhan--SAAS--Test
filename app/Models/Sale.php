<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Multitenancy\Models\Concerns\UsesTenantModel;

class Sale extends Model
{
    use UsesTenantModel;
    use HasFactory;

    protected $guarded = [];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    public function saleItem()
    {
        return $this->hasMany(SaleItem::class, 'sale_id', 'id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function saleBy()
    {
        return $this->belongsTo(User::class, 'sale_by');
    }

    public function variation()
    {
        return $this->hasMany(Variation::class, 'variant_id', 'id');
    }

    public function affliateCommission()
    {
        return $this->hasMany(AffliateCommission::class, 'affiliator_id', 'id');
    }

    public function CouerierOrder()
    {
        return $this->hasMany(CouerierOrder::class, 'sale_id', 'id');
    }

    public function accountReceive()
    {
        return $this->belongsTo(Bank::class, 'payment_method', 'id');
    }
}
