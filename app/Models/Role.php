<?php

namespace App\Models;

use App\Helpers\DBTool;
use App\Observers\RoleObserver;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Larapen\Admin\app\Models\Traits\Crud;
use Spatie\Permission\Models\Role as OriginalRole;

class Role extends OriginalRole
{
    use Crud;

    protected $fillable = ['name', 'guard_name', 'updated_at', 'created_at'];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    /**
     * Check Super Admin role
     * NOTE: Must use try {...} catch {...}
     *
     * @return bool
     */
    public static function checkSuperAdminRole()
    {
        try {
            $role = Role::where('name', Role::getSuperAdminRole())->first();
            if (empty($role)) {
                return false;
            }
        } catch (Exception $e) {
        }

        return true;
    }

    /**
     * @return string
     */
    public static function getSuperAdminRole()
    {
        $role = 'super-admin';

        return $role;
    }

    /**
     * Reset default roles
     * NOTE: Must use try {...} catch {...}
     *
     * @return Role|Model
     */
    public static function resetDefaultRole()
    {
        try {
            // Remove all current roles & their relationship
            $roles = Role::all();
            $roles->each(function ($item, $key) {
                if ($item->permissions()) {
                    $item->permissions()->detach();
                }
                $item->delete();
            });

            // Reset roles table ID auto-increment
            DB::statement('ALTER TABLE ' . DBTool::table(config('permission.table_names.roles')) . ' AUTO_INCREMENT = 1;');

            // Create the default Super Admin role
            $role = Role::where('name', Role::getSuperAdminRole())->first();
            if (empty($role)) {
                $role = Role::create(['name' => Role::getSuperAdminRole()]);
            }
        } catch (Exception $e) {
            return null;
        }

        return $role;
    }

    protected static function boot()
    {
        parent::boot();

        Role::observe(RoleObserver::class);
    }

    public function updateBtn($xPanel = false)
    {
        if (strtolower($this->name) == strtolower(Role::getSuperAdminRole())) {
            return null;
        }

        $url = admin_url('roles/' . $this->id . '/edit');

        $out = '';
        $out .= '<a href="' . $url . '" class="btn btn-xs btn-primary">';
        $out .= '<i class="fa fa-edit"></i> ';
        $out .= trans('admin.edit');
        $out .= '</a>';

        return $out;
    }

    public function deleteBtn($xPanel = false)
    {
        if (strtolower($this->name) == strtolower(Role::getSuperAdminRole())) {
            return null;
        }

        $url = admin_url('roles/' . $this->id);

        $out = '';
        $out .= '<a href="' . $url . '" class="btn btn-xs btn-danger" data-button-type="delete">';
        $out .= '<i class="fa fa-trash"></i> ';
        $out .= trans('admin.delete');
        $out .= '</a>';

        return $out;
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
