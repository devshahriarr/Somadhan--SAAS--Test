<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Multitenancy\Models\Concerns\UsesTenantModel;

class Attribute extends Model
{
    use UsesTenantModel;
    use HasFactory;

    protected $guarded = [];

    public function product_extra_field_manage()
    {
        return $this->hasMany(AttributeManage::class, 'extra_field_id', 'id');
    }
}
