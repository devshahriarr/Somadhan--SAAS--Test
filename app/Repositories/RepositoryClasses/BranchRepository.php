<?php

namespace App\Repositories\RepositoryClasses;

use App\Models\Branch;
use App\Repositories\RepositoryInterfaces\BranchInterface;

class BranchRepository implements BranchInterface
{
    public function getAllBranch()
    {
        return Branch::latest()->get();
    }

    public function editBranch($id)
    {
        return Branch::find($id);
    }
}
