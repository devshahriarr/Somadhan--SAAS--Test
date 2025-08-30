<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\EmployeeSalary;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\User;
use App\Repositories\RepositoryInterfaces\EmployeeInterface;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmployeeController extends Controller
{
    private $employee_repo;

    public function __construct(EmployeeInterface $employee_interface)
    {
        $this->employee_repo = $employee_interface;
    }

    public function EmployeeView()
    {
        if (Auth::user()->role === 'superadmin' || Auth::user()->role === 'admin') {
            $employees = Employee::all();
        } else {
            $employees = Employee::where('branch_id', Auth::user()->branch_id)->latest()->get();
        }

        return view('pos.employee.view_employee', compact('employees'));
    }

    //
    public function EmployeeAdd()
    {
        return view('pos.employee.add_employee');
    }

    //
    public function EmployeeStore(Request $request)
    {
        // dd($request->image);
        $request->validate([
            'full_name' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'address' => 'required',
            'salary' => 'required',
        ]);
        if ($request->image) {
            $employee = new Employee;
            $imageName = rand().'.'.$request->image->extension();
            $request->image->move(public_path('uploads/employee'), $imageName);
            $employee->pic = $imageName;
        }
        $employee = new Employee;
        $employee->branch_id = Auth::user()->branch_id;
        $employee->full_name = $request->full_name;
        $employee->email = $request->email;
        $employee->phone = $request->phone;
        $employee->address = $request->address;
        $employee->salary = $request->salary;
        $employee->nid = $request->nid;
        $employee->designation = $request->designation;
        $employee->status = 0;
        $employee->pic = $imageName ?? '';
        $employee->created_at = Carbon::now();
        $employee->save();
        $notification = [
            'message' => 'Employee Added Successfully',
            'alert-type' => 'info',
        ];

        return redirect()->route('employee.view')->with($notification);
    }

    //
    public function EmployeeEdit($id)
    {
        $employees = $this->employee_repo->EditEmployee($id);

        return view('pos.employee.edit_employee', compact('employees'));
    }

    //
    public function EmployeeUpdate(Request $request, $id)
    {
        // dd($request->all());
        $request->validate([
            'full_name' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'address' => 'required',
            'salary' => 'required',
        ]);
        $employee = Employee::findOrFail($id);
        $employee->branch_id = Auth::user()->branch_id;
        $employee->full_name = $request->full_name;
        $employee->email = $request->email;
        $employee->phone = $request->phone;
        $employee->address = $request->address;
        $employee->salary = $request->salary;
        $employee->nid = $request->nid;
        $employee->designation = $request->designation;
        $employee->status = 0;
        if ($request->image) {
            $imageName = rand().'.'.$request->image->extension();
            $request->image->move(public_path('uploads/employee'), $imageName);
            $employee->pic = $imageName;
        }
        $employee->save();

        $notification = [
            'message' => 'Employee Updated Successfully',
            'alert-type' => 'info',
        ];

        return redirect()->route('employee.view')->with($notification);
    }

    //
    public function EmployeeDelete($id)
    {
        $employee = Employee::findOrFail($id);
        $path = public_path('uploads/employee/'.$employee->image);
        if (file_exists($path)) {
            @unlink($path);
        }
        $employee->delete();
        $notification = [
            'message' => 'Employee Deleted Successfully',
            'alert-type' => 'info',
        ];

        return redirect()->route('employee.view')->with($notification);
    }

    public function EmployeeProfile($id)
    {
        $employee = Employee::findOrFail($id);
        $employeeSalarys = EmployeeSalary::where('employee_id', $employee->id)->get();
        $users = User::where('employee_id', $employee->id)->first();
        $sales = Sale::where('sale_by', $users->id)->get();

        return view('pos.employee.employye_profile.employye_profile', compact('employee', 'employeeSalarys', 'sales'));
    }

    public function filterEmployeeProfileSale(Request $request)
    {
        $filter = $request->input('filter', 'today'); // Default to 'today'
        // dd($filter);
        $employeeId = $request->query('id');
        $users = User::where('employee_id', $employeeId)->first();
        $salesQuery = Sale::where('sale_by', $users->id);
        // dd( $salesQuery);
        switch ($filter) {
            case 'today':
                $salesQuery->whereDate('sale_date', Carbon::today());
                break;

            case 'weekly':
                $salesQuery->whereBetween('sale_date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                    ->whereMonth('sale_date', Carbon::now()->month)
                    ->whereYear('sale_date', Carbon::now()->year);
                break;

            case 'monthly':
                $salesQuery->whereMonth('sale_date', Carbon::now()->month)
                    ->whereYear('sale_date', Carbon::now()->year);
                break;
            case 'yearly':
                $salesQuery->whereYear('sale_date', Carbon::now()->year);
                break;
        }

        $sale = $salesQuery->get();

        return response()->json(['sale' => $sale]);
    }

    public function filterEmployeeProfilePurchase(Request $request)
    {
        $filter = $request->input('filter', 'today'); // Default to 'today'
        // dd($filter);
        $employeeId = $request->query('id');
        $users = User::where('employee_id', $employeeId)->first();
        $purchaseQuery = Purchase::where('purchase_by', $users->id);

        switch ($filter) {
            case 'today':
                $purchaseQuery->whereDate('purchase_date', Carbon::today());
                break;

            case 'weekly':
                $purchaseQuery->whereBetween('purchase_date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                    ->whereMonth('purchase_date', Carbon::now()->month)
                    ->whereYear('purchase_date', Carbon::now()->year);
                break;

            case 'monthly':
                $purchaseQuery->whereMonth('purchase_date', Carbon::now()->month)
                    ->whereYear('purchase_date', Carbon::now()->year);
                break;
            case 'yearly':
                $purchaseQuery->whereYear('purchase_date', Carbon::now()->year);
                break;
        }

        $purchase = $purchaseQuery->get();

        return response()->json(['purchase' => $purchase]);
    }
}
