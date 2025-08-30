<?php

namespace App\Repositories\RepositoryClasses;

use App\Models\Employee;
use App\Repositories\RepositoryInterfaces\EmployeeInterface;

class EmployeeRepository implements EmployeeInterface
{
    public function ViewAllEmployee()
    {
        return Employee::all();
    }

    public function EditEmployee($id)
    {
        return Employee::findOrFail($id);
    }
}
