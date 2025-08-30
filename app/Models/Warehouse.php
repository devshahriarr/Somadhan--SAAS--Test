<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Multitenancy\Models\Concerns\UsesTenantModel;

class Warehouse extends Model
{
    use UsesTenantModel;
    use HasFactory;

    protected $guarded = [];
}
