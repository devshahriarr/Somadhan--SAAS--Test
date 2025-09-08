<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Multitenancy\Models\Concerns\UsesTenantModel;

class User extends Authenticatable
{
    use UsesTenantModel;
    use HasApiTokens, HasFactory, HasRoles,Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'tenant_id',
        'branch_id',
        'name',
        'email',
        'phone',
        'photo',
        'address',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // // ///for Role Permission /////
    // public static function getPermissiongroup()
    // {
    //     if (Auth::user()->id === 4) {
    //         $permission_groups = DB::table('permissions')->select('group_name')->orderBy('group_name')->get();

    //         return $permission_groups;
    //     } else {
    //         $permission_groups = DB::table('permissions')->select('group_name')->where('group_name', '!=', 'limit')->orderBy('group_name')->get();

    //         return $permission_groups;
    //     }
    // }

    public static function getPermissiongroup()
    {
        $query = DB::table('permissions')
            ->select('group_name')
            ->where('tenant_id', Tenant::current()->id) // Add tenant scoping
            ->orderBy('group_name');

        if (Auth::user()->id === 4) {
            return $query->get();
        } else {
            return $query->where('group_name', '!=', 'limit')->get();
        }
    }

    public static function getPermissionByGroupName($group_name)
    {
        $permissions = DB::table('permissions')->select('name', 'id')->where('group_name', $group_name)->get();

        return $permissions;
    }

    // public static function getPermissionByGroupName($group_name)
    // {
    //     return DB::table('permissions')
    //         ->select('name', 'id')
    //         ->where('group_name', $group_name)
    //         ->where('tenant_id', Tenant::current()->id) // Add tenant scoping
    //         ->get();
    // }


    public static function roleHasPermissions($role, $permissions)
    {
        $hasPermission = true;
        foreach ($permissions as $permission) {
            if (! $role->hasPermissionTo($permission->name)) {
                $hasPermission = false;

                return $hasPermission;
            }

            return $hasPermission;

        }
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'id');
    } //

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function devices()
    {
        return $this->hasMany(Device::class);
    }

    public function affiliate()
    {
        return $this->hasMany(Affiliator::class, 'branch_id', 'branch_id');
    }

    public function affliateCommission()
    {
        return $this->hasMany(AffliateCommission::class, 'branch_id', 'branch_id');
    }
}
