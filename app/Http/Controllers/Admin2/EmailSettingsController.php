<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\MailGunHelper;
use App\Models\EmailSetting;
use Illuminate\Http\Request;

class EmailSettingsController extends AdminBaseController
{

    public function email_setting_edit()
    {
        $email_setting = EmailSetting::first();
        return view('vendor.admin.paymentSetting.email_setting', compact('email_setting'));
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
        $email_stats = MailGunHelper::get_stats();

        return view('vendor.admin.email_stats', compact('email_stats'));
    }
    public function mailgun_failed_emails(){
        $email_stats = MailGunHelper::getBounces();
        return view('vendor.admin.failed_emails', compact('email_stats'));
    }
}
