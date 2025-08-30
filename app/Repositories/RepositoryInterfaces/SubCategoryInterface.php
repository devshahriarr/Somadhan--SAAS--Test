<?php

namespace App\Repositories\RepositoryInterfaces;

interface SubCategoryInterface
{
    public function getAllSubCategory();

    public function create(array $data);

    public function editData($id);
}
