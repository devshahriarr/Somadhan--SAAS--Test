<?php

namespace App\Repositories\RepositoryClasses;

use App\Models\Brand;
use App\Repositories\RepositoryInterfaces\BrandInterface;

class BrandRepository implements BrandInterface
{
    public function getAllBrand()
    {
        return Brand::all();
    }

    public function editData($id)
    {
        return Brand::findOrFail($id);
    }
}
