<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\MailGunHelper;
use App\Models\EmailSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class EmailSettingsController extends AdminBaseController
{

    public function email_setting_edit()
    {
        $email_setting = EmailSetting::first();
        $title = 'Email Settings';
        $breadcumbs = [
            [
                'title'=> 'Dashboard',
                'link'=> admin_url('dashboard')
            ],
            [
                'title'=> 'Email Settings',
                'link'=> 'javascript:void(0)'
            ]
        ];
        return view('admin.settings.email_setting', compact('email_setting','title','breadcumbs'));
    }

    public function email_setting_update(Request $request)
    {
        $id = $request->get('id');
        $data = [
            'status' => $request->get('status'),
            'email' => $request->get('email'),
            'key' => $request->get('key')
        ];
        if (!empty($id)) {
            EmailSetting::where('id', $id)->update($data);
        } else {
            EmailSetting::create($data);
        }
        flash('Changes successfully saved!')->info();
        return redirect()->back();
    }

    public function check_email_setting_update(Request $request)
    {
        $id = $request['id'];

        if (empty($id)) {
            flash('Please try Again')->error();
            return redirect()->back();
        }

        $obj = EmailSetting::find($id);
        $obj->check_email_api_key = $request->get('check_email_api_key');
        $obj->check_email_status = $request->get('check_email_status');

        if ($obj->update()) {
            flash('Changes successfully saved!')->info();
            return redirect()->back();
        } else {
            flash('Please try Again!')->error();
            return redirect()->back();
        }
    }

    public function update_mailgun_settings(Request $request)
    {
        $id = $request->get('id');
        $data = [
            'status_mailgun' => $request->get('status_mailgun'),
            'domain_name' => $request->get('domain_name'),
            'api_key' => $request->get('api_key')
        ];
        if (!empty($id)) {
            EmailSetting::where('id', $id)->update($data);
        } else {
            EmailSetting::create($data);
        }
        flash('Changes successfully saved!')->info();
        return redirect()->back();
    }

    public function email_stats(Request $request){

        $email_stats = Cache::remember('email_stats.', 3000000, function (){
                return MailGunHelper::get_stats();
        });

        $sorted_stats = Cache::remember('sorted_stats.', 3000000, function () use ($email_stats){
                return $sorted_stats = collect($email_stats->getStats())->sortByDesc(function($item) {
                    return $item->getTime()->getTimestamp();
                });
        });
        
        $title = 'Email Stats';
        $breadcumbs = [
            [
                'title'=> 'Dashboard',
                'link'=> admin_url('dashboard')
            ],
            [
                'title'=> 'Email Stats',
                'link'=> 'javascript:void(0)'
            ]
        ];

        return view('admin.settings.email_stats', compact('email_stats','sorted_stats','title','breadcumbs'));
    }


    public function mailgun_failed_emails(){
        return view('admin.settings.failed_emails');
    }

    public function mailgun_ajax(Request $request){
        $email_stats = MailGunHelper::getBounces();
        $counter = 0;
        $data = [];
        foreach ($email_stats->getItems() as $key => $item) {
            $counter = $key+1;
            $data[$key][] = $counter;
            $data[$key][] = $item->getRecipient();
            $data[$key][] = $item->getDeliveryStatus()['message'];
            $data[$key][] = date('Y-m-d H:i:s', $item->getEventDate()->getTimestamp());
        }
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(
            ['draw' => $request->get('draw'),
                'recordsTotal' => $counter,
                'recordsFiltered' =>  $counter,
                'data' => $data,
            ]
        );
        die;
    }
}
