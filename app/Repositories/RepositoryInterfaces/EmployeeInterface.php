<?php

namespace App\Repositories\RepositoryInterfaces;

interface EmployeeInterface
{
    public function ViewAllEmployee();

    public function EditEmployee($id);
}
