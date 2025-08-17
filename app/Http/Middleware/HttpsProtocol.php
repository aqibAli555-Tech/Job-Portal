<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class HttpsProtocol
{
    /**
     * Redirects any non-secure requests to their secure counterparts.
     *
     * @param Request $request
     * @param Closure $next
     * @return RedirectResponse
     */
    public function handle($request, Closure $next)
    {
        if (config('larapen.core.forceHttps') == true) {
            // $request->setTrustedProxies([$request->getClientIp()], $request::HEADER_X_FORWARDED_ALL);
            if (!$request->secure()) {
                /* $request->server('HTTP_X_FORWARDED_PROTO') != 'https' */
                // Production is not currently secure
                // return redirect()->secure($request->getRequestUri());
            }
        }

        return $next($request);
    }
}
