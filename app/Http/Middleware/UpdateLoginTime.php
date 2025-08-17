<?php

namespace App\Http\Middleware;

use App\Models\Permission;
use App\Models\User;
use Carbon\Carbon;
use Closure;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Lab404\Impersonate\Services\ImpersonateManager;
use Prologue\Alerts\Facades\Alert;

class UpdateLoginTime
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
        if (auth()->check()) {
            $manager = app()->make(ImpersonateManager::class);
            if ($manager->isImpersonating()) {
                return $next($request);
            } else {
                $today_date = date('Y-m-d');
                $login_date = auth()->user()->last_login_at->format('Y-m-d');
                if ($today_date > $login_date) {
                    User::update_login_time();
                }
            }
        }
        return $next($request);
    }
}
