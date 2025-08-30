<?php

namespace App\Repositories\RepositoryInterfaces;

interface BankInterface
{
    public function getAllBank();

    public function editBank($id);
}
