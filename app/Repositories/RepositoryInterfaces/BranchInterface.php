<?php

namespace App\Repositories\RepositoryInterfaces;

interface BranchInterface
{
    public function getAllBranch();

    public function editBranch($id);
}
