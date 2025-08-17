<?php

namespace App\Http\Controllers\Account;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\Activities;
use Illuminate\Http\Request;

class ActivitylogController extends AccountBaseController
{
    public function index()
    {
        if (!Helper::check_permission(11)) {
            flash(t("You do not have permission to access this module"))->error();
            return redirect('/');
        }

        if (auth()->user()->user_type_id == 1 && empty(auth()->user()->parent_id)) {
            flash(t("Please change account to child company to view this page."))->error();
            return redirect('/');
        }

        view()->share('pagePath', 'activity_logs');
        view()->share([
            'title' => t('My Activity Logs'),
            'description' => t('My Activity Logs'),
            'keywords' => t('My Activity Logs'),
            // Add more variables as needed
        ]);
        return appView('account.activitylog');
    }

    public function ajax(Request $request)
    {
        $data = [];
        $pagelog = Activities::get_company_log($request);
        $pagelog_count = Activities::get_company_log_count($request);
        $data = [];
        if($pagelog->count() > 0){
            foreach ($pagelog as $key => $log){
                $description = $result = str_replace("{{company_name}}", 'You', $log->description);
                $data[$key][] = '<td>'.$log->created_at.'</td>';
                $data[$key][] = '<td>'.$description.'</td>';
            }
        }

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(
            ['draw' => $request->get('draw'),
                'recordsTotal' => $pagelog_count,
                'recordsFiltered' =>  $pagelog_count,
                'data' => $data,
            ]
        );
        die;
    }
}
