<?php

namespace App\Http\Controllers;

use App\Models\Pagelog;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function __construct()
    {
        date_default_timezone_set("Asia/Kuwait");
        $this->middleware(function ($request, $next) {

            if (auth()->check()) {
                $data['userId'] = auth()->user()->id;
            } else {
                $data['userId'] = 0;
            }

             if (isset($_SERVER['HTTP_USER_AGENT'])) {
                $userAgent = $request->header('User-Agent');
                if (strpos($userAgent, 'Mozilla') !== false || strpos($userAgent, 'Chrome') !== false || strpos($userAgent, 'Safari') !== false || strpos($userAgent, 'Edge') !== false) {
                    return $next($request);
                } else {
                   
                    $data['route'] = url()->full();
                    $data['userIP'] = $request->ip();
                    $data['userAgent'] = $request->server('HTTP_USER_AGENT');
                    $data['created_time'] = date('Y-m-d H:i:s');
                    $data['data'] = json_encode($_REQUEST);
                    $data['session_data'] = json_encode(session()->all());
                    $data['session_id'] = session()->getId();
                    $data['referrer'] = !empty($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : "";
                    if ($request->isMethod('get')) {
                        $data['method'] = 'get';
                    } else {
                        $data['method'] = 'post';
                    }
                    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                        $data['isAjax'] = 1;
                    } else {
                        $data['isAjax'] = 0;
                    }
                    Pagelog::insert($data);
                }
              
            }
            return $next($request);
        });
    }

}
