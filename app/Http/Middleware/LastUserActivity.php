<?php

namespace App\Http\Middleware;

use App\Helpers\Date;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class LastUserActivity
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Exception for Install & Upgrade Routes
        if (
            Str::contains(Route::currentRouteAction(), 'InstallController')
            || Str::contains(Route::currentRouteAction(), 'UpgradeController')
        ) {
            return $next($request);
        }

        // Waiting time in minutes
        $waitingTime = 5;

        if (auth()->check()) {
            if (config('settings.optimization.cache_driver') == 'array') {
                if (Schema::hasColumn('users', 'last_activity')) {
                    $user = auth()->user();
                    if ($user->last_activity < Carbon::now(Date::getAppTimeZone())->subMinutes($waitingTime)->format('Y-m-d H:i:s')) {
                        $user = auth()->user();
                        $user->last_activity = new Carbon;
                        $user->timestamps = false;
                        $user->save();
                    }
                }
            } else {
                $expiresAt = Carbon::now(Date::getAppTimeZone())->addMinutes($waitingTime);
                Cache::store('file')->put('user-is-online-' . auth()->user()->id, true, $expiresAt);
            }
        }

        return $next($request);
    }
}
