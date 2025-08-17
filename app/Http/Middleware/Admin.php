<?php

namespace App\Http\Middleware;

use App\Models\Permission;
use App\Models\User;
use Closure;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Prologue\Alerts\Facades\Alert;

class Admin
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @param null $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {

        if (!auth()->check()) {
            // Block access if user is guest (not logged in)
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'message' => trans('admin.unauthorized'),
                    'redirect' => admin_url('/login')
                ], 401);

//                return response(trans('admin.unauthorized'), 401);
            } else {


                if ($request->path() != admin_uri('login')) {
                    Alert::error(trans('admin.unauthorized'))->flash();
                    return redirect()->guest(admin_uri('login'));
                }
            }
        } else {
//            if (auth()->user()->is_admin != 1) {
//                auth()->logout();
//                return redirect()->guest(admin_uri('login'));
//            }
            try {
                $aclTableNames = config('permission.table_names');
                if (isset($aclTableNames['permissions'])) {
                    if (!Schema::hasTable($aclTableNames['permissions'])) {
                        return $next($request);
                    }
                }
            } catch (Exception $e) {
                return $next($request);
            }

            $user = User::query()->count();

            if (!($user == 1)) {
                // If user does //not have this permission
                if (!auth()->guard($guard)->user()->can(Permission::getStaffPermissions()) || auth()->user()->is_admin != 1) {
                    if ($request->ajax() || $request->wantsJson()) {
                        return response()->json([
                            'message' => trans('admin.unauthorized'),
                            'redirect' => admin_url('/login')
                        ], 401);

                    } else {
                        auth()->logout();
                        Alert::error(trans('admin.unauthorized'))->flash();

                        return redirect()->guest(admin_uri('login'));
                    }
                }
            }
        }

        return $next($request);
    }
}
