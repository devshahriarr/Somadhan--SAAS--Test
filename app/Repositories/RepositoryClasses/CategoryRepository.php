<?php

namespace App\Repositories\RepositoryClasses;

use App\Models\Category;
use App\Repositories\RepositoryInterfaces\CategoryInterface;

class CategoryRepository implements CategoryInterface
{
    public function getAllCategory()
    {
        return Category::all();
    }
}
