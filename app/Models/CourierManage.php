<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourierManage extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function courier_add()
    {
        return $this->hasMany(CourierAdd::class, 'courier_id', 'id');
    }
}
