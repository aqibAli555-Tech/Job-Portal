<?php

namespace App\Http\Middleware;

use App\Helpers\UrlGen;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param Request $request
     * @return string
     */
    protected function redirectTo($request)
    {
        if (!$request->expectsJson()) {
            if (isFromAdminPanel()) {
//				return route(admin_uri('login'));
                return redirect('admin/login');

            } else {
                return route(UrlGen::loginPath());
            }
        }
    }
}
