<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Multitenancy\Models\Concerns\UsesTenantModel;

class EmployeeSalary extends Model
{
    use UsesTenantModel;
    use HasFactory;

    protected $guarded = [];

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'id');
    }

    public function emplyee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }
}
