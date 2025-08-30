<?php

namespace App\Repositories\RepositoryClasses;

use App\Models\Customer;
use App\Repositories\RepositoryInterfaces\CustomerInterfaces;

class CustomerRepository implements CustomerInterfaces
{
    public function ViewAllCustomer()
    {
        return Customer::latest()->get();
    }

    public function EditCustomer($id)
    {
        return Customer::findOrFail($id);
    }
}
