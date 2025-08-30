<?php

namespace App\Repositories\RepositoryClasses;

use App\Models\SubCategory;
use App\Repositories\RepositoryInterfaces\SubCategoryInterface;

class SubCategoryRepository implements SubCategoryInterface
{
    public function getAllSubCategory()
    {
        return SubCategory::all();
    }

    public function create(array $data)
    {
        return SubCategory::create($data);
    }

    public function editData($id)
    {
        return SubCategory::findOrFail($id);
    }
}
