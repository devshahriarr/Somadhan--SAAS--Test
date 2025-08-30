<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourierAdd extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function courier_manage()
    {
        return $this->belongsTo(CourierManage::class, 'courier_id', 'id');
    }
}
