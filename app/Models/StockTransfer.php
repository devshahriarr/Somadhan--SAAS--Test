<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Multitenancy\Models\Concerns\UsesTenantModel;

class StockTransfer extends Model
{
    use UsesTenantModel;
    use HasFactory;

    protected $guarded = [];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function variation()
    {
        return $this->belongsTo(Variation::class, 'variation_id', 'id');
    }

    public function fromWarehouse()
    {
        return $this->belongsTo(Warehouse::class, 'from_warehouse_id', 'id');
    }

    public function toWarehouse()
    {
        return $this->belongsTo(Warehouse::class, 'to_warehouse_id', 'id');
    }

    public function fromRack()
    {
        return $this->belongsTo(WarehouseRack::class, 'from_rack_id', 'id');
    }

    public function toRack()
    {
        return $this->belongsTo(WarehouseRack::class, 'to_rack_id', 'id');
    }

    public function fromBranch()
    {
        return $this->belongsTo(Branch::class, 'from_branch_id', 'id');
    }

    public function toBranch()
    {
        return $this->belongsTo(Branch::class, 'to_branch_id', 'id');
    }
}
