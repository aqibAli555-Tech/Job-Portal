<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Foundation\Http\Exceptions\MaintenanceModeException;
use Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode as Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

class CheckForMaintenanceMode extends Middleware
{
    /**
     * The URIs that should be reachable while maintenance mode is enabled.
     *
     * @var array
     */
    protected $except = [];

    /**
     * Create a new middleware instance.
     *
     * @param Application $app
     * @return void
     */
    public function __construct(Application $app)
    {
        parent::__construct($app);

        $this->except = [
            admin_uri() . '/*',
            admin_uri(),
            'upgrade',
            'upgrade/run',
        ];
    }

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     *
     * @throws HttpException
     */
    public function handle($request, Closure $next)
    {
        if ($this->app->isDownForMaintenance() && !$this->shouldPassThrough($request)) {
            $down = $this->app->storagePath() . '/framework/down';
            if (file_exists($down)) {
                // Decode the "down" file's JSON...
                $data = json_decode(file_get_contents($down), true);

                // Fix Laravel 8 issue
                $data['time'] = time();
                $data['message'] = null;

                throw new MaintenanceModeException($data['time'], $data['retry'], $data['message']);
            }
        }

        return $next($request);
    }

    /**
     * @param $request
     * @return bool
     */
    protected function shouldPassThrough($request)
    {
        $canPass = false;

        if ($this->inExceptArray($request) || $this->shouldPassThroughIp($request)) {
            $canPass = true;
        }

        return $canPass;
    }

    /**
     * @param $request
     * @return bool
     */
    protected function shouldPassThroughIp($request)
    {
        $exceptOwnIp = config('larapen.core.exceptOwnIp');
        if (is_array($exceptOwnIp)) {
            if (in_array($request->ip(), $exceptOwnIp)) {
                return true;
            }
        }

        return false;
    }
}
