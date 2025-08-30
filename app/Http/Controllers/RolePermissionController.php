<?php

namespace App\Http\Controllers;

use App\Models\Affiliator;
use App\Models\Branch;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionController extends Controller
{
    // ///////////////////////Permission////////////////////////////

    public function AllPermissionView()
    {
        $permission = Permission::all();

        return view('pos.role_and_permission.permission.all_permission', compact('permission'));
    }

    public function AddPermission()
    {
        return view('pos.role_and_permission.permission.add_permission');
    }

    public function StorePermission(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:permissions,name',
            'group_name' => 'required',
        ]);

        $permission = Permission::create([
            'name' => $request->name,
            'group_name' => $request->group_name,
        ]);
        $notification = [
            'message' => 'Permission Added Successfully',
            'alert-type' => 'info',
        ];

        return redirect()->route('all.permission')->with($notification);
    }

    //
    public function EditPermission($id)
    {
        $permissions = Permission::findOrFail($id);

        return view('pos.role_and_permission.permission.edit_permission', compact('permissions'));
    }

    public function updatePermission(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);
        $id = $request->permission_id;
        $permission = Permission::findOrFail($id)->update([
            'name' => $request->name,
            'group_name' => $request->group_name,
        ]);
        $notification = [
            'message' => 'Permission Updated Successfully',
            'alert-type' => 'info',
        ];

        return redirect()->route('all.permission')->with($notification);
    }

    public function DeletePermission($id)
    {
        $permission = Permission::findOrFail($id)->delete();
        $notification = [
            'message' => 'Permission Deleted Successfully',
            'alert-type' => 'info',
        ];

        return redirect()->back()->with($notification);
    }

    // ///////////////////////Role////////////////////////////
    public function AllRoleView()
    {
        $role = Role::all();

        return view('pos.role_and_permission.role.all_role', compact('role'));
    }

    //
    public function AddRole()
    {
        return view('pos.role_and_permission.role.add_role');
    }

    //
    public function StoreRole(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name',
        ]);
        $role = Role::create([
            'name' => $request->name,
        ]);
        $notification = [
            'message' => 'Role Added Successfully',
            'alert-type' => 'info',
        ];

        return redirect()->route('all.role')->with($notification);
    }

    public function EditRole($id)
    {
        $roles = Role::findOrFail($id);

        return view('pos.role_and_permission.role.edit_role', compact('roles'));
    }

    public function updateRole(Request $request)
    {
        $id = $request->role_id;
        $role = Role::findOrFail($id)->update([
            'name' => $request->name,
        ]);
        $notification = [
            'message' => 'Role Updated Successfully',
            'alert-type' => 'info',
        ];

        return redirect()->route('all.role')->with($notification);
    }

    public function DeleteRole($id)
    {
        Role::findOrFail($id)->delete();
        $notification = [
            'message' => 'Role Deleted Successfully',
            'alert-type' => 'info',
        ];

        return redirect()->back()->with($notification);
    }

    // /////////////////////////////Role In Permission All Methods////////////////////////
    public function AddRolePermission()
    {
        $role = Role::all();
        $permission = Permission::all();

        $permission_group = User::getPermissiongroup();

        return view('pos.role_and_permission.role_permission.add_role_permission', compact('role', 'permission', 'permission_group'));
    }

    //
    public function StoreRolePermission(Request $request)
    {
        $data = [];
        $permissions = $request->permission;
        foreach ($permissions as $key => $item) {
            $data['role_id'] = $request->role_id;
            $data['permission_id'] = $item;
            DB::table('role_has_permissions')->insert($data);
        }
        $notification = [
            'message' => 'Role Permission Added Successfully',
            'alert-type' => 'info',
        ];

        return redirect()->route('all.role.permission')->with($notification);
    }

    public function AllRolePermission()
    {
        $role = Role::all();

        return view('pos.role_and_permission.role_permission.all_role_permission', compact('role'));
    }

    //
    public function AdminRoleEdit($id)
    {
        $role = Role::findOrFail($id);
        $permission = Permission::all();
        $permission_group = User::getPermissiongroup();

        return view('pos.role_and_permission.role_permission.edit_role_permission', compact('role', 'permission', 'permission_group'));
    }

    public function AdminRoleUpdate(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'permission' => 'array',
            'permission.*' => 'integer|exists:permissions,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $permissions = $request->permission;
        if (! empty($permissions)) {
            $permissionNames = Permission::whereIn('id', $permissions)->pluck('name')->toArray();
            $role->syncPermissions($permissionNames);
        } else {
            $role->syncPermissions([]);
        }

        $notification = [
            'message' => 'Role Permission Updated Successfully',
            'alert-type' => 'info',
        ];

        return redirect()->route('all.role.permission')->with($notification);
    }

    public function AdminRoleDelete($id)
    {
        $role = Role::findOrFail($id);
        if (! is_null($role)) {
            $role->delete();
        }
        $notification = [
            'message' => 'Role Permission Deleted Successfully',
            'alert-type' => 'info',
        ];

        return redirect()->route('all.role.permission')->with($notification);
    }

    // //////////////////All Admin Manage Method///////////////////////////
    public function AllAdminView()
    {
        $user = User::all();

        return view('pos.role_and_permission.admin_manage.all_admin_view', compact('user'));
    }

    public function AddAdmin()
    {
        $role = Role::all();
        $branch = Branch::all();

        return view('pos.role_and_permission.admin_manage.add_admin', compact('role', 'branch'));
    }

    public function AdminStore(Request $request)
    {
        // Fetch the company associated with the authenticated user
        $company = Auth::user()->company;
        // Get the user limit for the company
        // dd($company->userLimit);
        $userLimit = $company->userLimit->user_limit;

        // Count the current number of users in the company
        $currentUserCount = $company->users()->count();
        // dd($currentUserCount);

        // Check if the current user count has reached or exceeded the user limit
        if ($currentUserCount >= $userLimit) {
            // Find the super admin of the company
            // $superAdmin = $company->users()->whereRole('super-admin')->first();
            // Notify the super admin that the user limit has been reached
            // $superAdmin->notify(new UserLimitReached($company));

            // Redirect back with an error message
            return redirect()->back()->with('error', 'User limit reached. Please upgrade your package to add more users.');
        }

        // Validate the request data //
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'branch_id' => 'required',
            'password' => 'required',
            'role_id' => 'required',
        ]);

        // Create a new user instance
        $roleId = $request->role_id;

        // Find the role by ID and get the name
        $role = Role::findOrFail($roleId);
        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->phone = $request->phone;
        $user->address = $request->address;
        $user->branch_id = $request->branch_id;
        $user->employee_id = $request->employee_id;
        $user->role = strtolower(str_replace(' ', '', $role->name));
        $user->save();
        // Assign the role to the user if provided
        if ($request->role_id) {
            $user->assignRole($request->role_id);
        }

        if ($request->commission_rate && $user) {

            $affiliator = new Affiliator;
            $affiliator->name = $request->name;
            $affiliator->phone = $request->phone;
            $affiliator->branch_id = $request->branch_id;
            $affiliator->user_id = $user->id;
            $affiliator->commission_type = $request->commission_type;
            if ($request->commission_rate) {
                $affiliator->commission_rate = $request->commission_rate;
            }

            $affiliator->commission_state = $request->commission_state;
            $affiliator->save();
        }

        // Redirect with a success message
        return redirect()->route('admin.all')->with([
            'message' => 'New Admin User Inserted Successfully',
            'alert-type' => 'info',
        ]);
    }

    public function AdminManageEdit($id)
    {
        $user = User::findOrFail($id);
        $role = Role::all();
        $branch = Branch::all();

        return view('pos.role_and_permission.admin_manage.edit_admin', compact('user', 'role', 'branch'));
    }

    //
    public function AdminManageUpdate(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'branch_id' => 'required',
            'role_id' => 'required',
        ]);
        $roleId = $request->role_id;

        // Find the role by ID and get the name
        $role = Role::findOrFail($roleId);
        $user = User::findOrFail($id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->address = $request->address;
        $user->branch_id = $request->branch_id;
        $user->role = strtolower(str_replace(' ', '', $role->name));
        $user->save();
        $user->roles()->detach();
        if ($request->role_id) {
            $user->assignRole($request->role_id);
        }

        if ($request->commission_type && $user) {
            $affiliator = Affiliator::where('user_id', $user->id)->first();
            if (! $affiliator) {
                $affiliator = new Affiliator;
            }
            $affiliator->name = $request->name;
            $affiliator->phone = $request->phone;
            $affiliator->branch_id = $request->branch_id;
            $affiliator->user_id = $user->id;
            $affiliator->commission_type = $request->commission_type;
            $affiliator->commission_rate = $request->commission_rate;
            $affiliator->commission_state = $request->commission_state;
            $affiliator->save();
        }

        $notification = [
            'message' => 'Admin User Updated Successfully',
            'alert-type' => 'info',
        ];

        return redirect()->route('admin.all')->with($notification);
    }

    //
    public function AdminManageDelete($id)
    {
        $user = User::findOrFail($id);
        if (! is_null($user)) {
            $user->delete();
        }
        $notification = [
            'message' => 'Admin User Deleted Successfully',
            'alert-type' => 'info',
        ];

        return redirect()->route('admin.all')->with($notification);
    }

    public function EmployeedData($id)
    {
        $employee = Employee::findOrFail($id);
        // dd($employee);
        if ($employee) {
            return response()->json([
                'success' => true,
                'employee' => [
                    'name' => $employee->full_name,
                    'email' => $employee->email,
                    'phone' => $employee->phone,
                    'address' => $employee->address,
                ],
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Employee not found.',
            ]);
        }
    }
}
