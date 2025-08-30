<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Multitenancy\Models\Concerns\UsesTenantModel;

class StockAdjustment extends Model
{
    use UsesTenantModel;
    use HasFactory;

    protected $guarded = [];

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function rack()
    {
        return $this->belongsTo(WarehouseRack::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function userName()
    {
        return $this->belongsTo(User::class, 'adjusted_by', 'id'); // Adjust 'user_id' if needed
    }

    public function items()
    {
        return $this->hasMany(StockAdjustmentItems::class, 'adjustment_id', 'id');
    }
}
