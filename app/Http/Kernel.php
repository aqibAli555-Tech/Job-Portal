<?php

namespace App\Http;

use App\Http\Middleware\Admin;
use App\Http\Middleware\Authenticate;
use App\Http\Middleware\BannedUser;
use App\Http\Middleware\CheckForMaintenanceMode;
use App\Http\Middleware\DemoRestriction;
use App\Http\Middleware\EncryptCookies;
use App\Http\Middleware\HtmlMinify;
use App\Http\Middleware\HttpsProtocol;
use App\Http\Middleware\InstallationChecker;
use App\Http\Middleware\LastUserActivity;
use App\Http\Middleware\NoHttpCache;
use App\Http\Middleware\OnlyAjax;
use App\Http\Middleware\RedirectIfAuthenticated;
use App\Http\Middleware\ResourceHints;
use App\Http\Middleware\SetBrowserLocale;
use App\Http\Middleware\SetCountryLocale;
use App\Http\Middleware\SetDefaultLocale;
use App\Http\Middleware\TipsMessages;
use App\Http\Middleware\TrimStrings;
use App\Http\Middleware\TrustProxies;
use App\Http\Middleware\UpdateLoginTime;
use App\Http\Middleware\VerifyCsrfToken;
use App\Http\Middleware\XSSProtection;
use Illuminate\Auth\Middleware\AuthenticateWithBasicAuth;
use Illuminate\Auth\Middleware\Authorize;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Foundation\Http\Kernel as HttpKernel;
use Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull;
use Illuminate\Foundation\Http\Middleware\ValidatePostSize;
use Illuminate\Http\Middleware\SetCacheHeaders;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Illuminate\Routing\Middleware\ValidateSignature;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Laravel\Passport\Http\Middleware\CheckClientCredentials;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        CheckForMaintenanceMode::class,
        ValidatePostSize::class,
        TrimStrings::class,
        ConvertEmptyStringsToNull::class,
        TrustProxies::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            EncryptCookies::class,
            AddQueuedCookiesToResponse::class,
            StartSession::class,
            // \Illuminate\Session\Middleware\AuthenticateSession::class,
            ShareErrorsFromSession::class,
            VerifyCsrfToken::class,
            SubstituteBindings::class,

            SetBrowserLocale::class,
            SetCountryLocale::class,
            SetDefaultLocale::class,
            TipsMessages::class,
            XSSProtection::class,
            BannedUser::class,
            HttpsProtocol::class,
            ResourceHints::class,
            HtmlMinify::class,
            LastUserActivity::class,
            UpdateLoginTime::class,

        ],

        'admin' => [
            EncryptCookies::class,
            AddQueuedCookiesToResponse::class,
            StartSession::class,
            ShareErrorsFromSession::class,
            VerifyCsrfToken::class,
            SubstituteBindings::class,

            Admin::class,
            XSSProtection::class,
            BannedUser::class,
            HttpsProtocol::class,
            ResourceHints::class,
        ],

        'api' => [
            'throttle:60,1',
            'bindings',
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth' => Authenticate::class,
        'auth.basic' => AuthenticateWithBasicAuth::class,
        'bindings' => SubstituteBindings::class,
        'cache.headers' => SetCacheHeaders::class,
        'can' => Authorize::class,
        'guest' => RedirectIfAuthenticated::class,
        'signed' => ValidateSignature::class,
        'throttle' => ThrottleRequests::class,

        'client' => CheckClientCredentials::class,

        'banned.user' => BannedUser::class,

        'install.checker' => InstallationChecker::class,
        'no.http.cache' => NoHttpCache::class,
        'only.ajax' => OnlyAjax::class,
        'demo.restriction' => DemoRestriction::class,
    ];

    /**
     * The priority-sorted list of middleware.
     *
     * This forces non-global middleware to always be in the given order.
     *
     * @var array
     */
    protected $middlewarePriority = [
        StartSession::class,
        ShareErrorsFromSession::class,
        Authenticate::class,
        AuthenticateSession::class,
        SubstituteBindings::class,
        Authorize::class,
        XSSProtection::class,
        SetBrowserLocale::class,
        SetCountryLocale::class,
        ResourceHints::class,
        HtmlMinify::class,
    ];
}
