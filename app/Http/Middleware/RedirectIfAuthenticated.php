<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @param string|null $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (auth()->guard($guard)->check()) {
            if ($request->segment(1) == admin_uri()) {
                return redirect(admin_uri() . '/?login=success');
            } else {
                return redirect('/?login=success');
            }
        }

        return $next($request);
    }
}
