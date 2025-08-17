<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class OnlyAjax
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
        if (!$request->ajax()) {
            abort(404);
        }

        return $next($request);
    }
}
