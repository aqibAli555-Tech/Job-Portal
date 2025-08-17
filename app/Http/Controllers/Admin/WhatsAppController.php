<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ExportHelper;
use App\Http\Controllers\Controller;
use App\Models\UserSetting;
use Illuminate\Http\Request;

class WhatsAppController extends AdminBaseController
{
    public function ajax(Request $request)
    {
      $data = [];
      $whatsapp_users = UserSetting::get_user_setting($request);
      $filtered = UserSetting::user_setting_filter_count($request);
      $whatsapp_count = UserSetting::user_setting_filter_count($request);
      foreach ($whatsapp_users as $key => $user) {
        $data[$key][] = $user->id;
        $data[$key][] = $user->user->name;
        $data[$key][] = $user->user->email;
        $data[$key][] = $user->whatsapp_number;
      }

        header('Content-Type: application/json; charset=utf-8');
            echo json_encode(
                [
                    'draw' => $request->get('draw'),
                    'recordsTotal' => $whatsapp_count,
                    'recordsFiltered' => $filtered,
                    'data' => $data
                ]
            );
            die;
    }

    public function index(Request $request)
    {
        $title = 'WhatsApp Users';
        $breadcumbs = [
            [
                'title' => 'Dashboard',
                'link' => admin_url('dashboard')
            ],
            [
                'title' => 'WhatsApp Users',
                'link' => 'javascript:void(0)'
            ]
        ];
        return view('admin.settings.whatsApp', compact('title', 'breadcumbs'));
    }

    public function exportWhatsApp(Request $request)
    {
        $query = UserSetting::with('user')
                ->whereNotNull('whatsapp_number');

            if ($request->get('search')) {
                $search = strtolower($request->get('search'));
                $query->whereHas('user', function ($q) use ($search) {
                    $q->whereRaw('LOWER(name) like ?', ["%{$search}%"])
                    ->orWhereRaw('LOWER(email) like ?', ["%{$search}%"]);
                });
            }

            $records = $query->get();

            $headers = ['ID', 'Name', 'Email', 'WhatsApp Number'];
            $data = [];

            foreach ($records as $record) {
                $data[] = [
                    $record->id,
                    $record->user->name,
                    $record->user->email,
                    $record->whatsapp_number,
                ];
            }
            
            ExportHelper::exportInExcel($data, $headers, 'whatsapp_users');
    }
}
