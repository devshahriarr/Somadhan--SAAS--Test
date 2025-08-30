<?php

namespace App\Repositories\SaleRepository;

use App\Models\Sale;
use App\Repositories\RepositoryIntefaces\SaleInterface;

class SaleRepository implements SaleInterface
{
    public function getAllCategory()
    {
        return Sale::all();
    }
}
