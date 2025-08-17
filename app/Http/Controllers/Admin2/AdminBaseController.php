<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;


class AdminBaseController extends Controller
{
    public function __construct()
    {
        date_default_timezone_set("Asia/Kuwait");
        $this->middleware('admin');
        parent::__construct();
        if (auth()->user()) {
            return redirect('login');
        }
    }
}

?>