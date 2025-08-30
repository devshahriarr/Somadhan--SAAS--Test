<?php

namespace App\Repositories\RepositoryClasses;

use App\Models\Damage;
use App\Repositories\RepositoryInterfaces\DamageInterface;

class DamageRepository implements DamageInterface
{
    public function create($data)
    {

        // @dd($data->product);
        // $damage = new Damage;
        // $damage->product_id = $data->product;
        // $damage->qty = $data->pc;

        // $damage->date = $data->date;
        // $damage->note = $data->note;
        // $damage->save();
        return back();
    }

    public function ViewAllDamage()
    {
        return Damage::latest()->get();
    }
}
