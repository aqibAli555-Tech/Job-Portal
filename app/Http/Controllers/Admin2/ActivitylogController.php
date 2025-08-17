<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Auth\Traits\VerificationTrait;
use App\Models\Activities;
use App\Models\Applicant;
use App\Models\ContactCardProblems;
use App\Models\EmailSetting;
use App\Models\Pagelog;
use App\Models\PaymentSetting;
use App\Models\Unlock;
use App\Models\User;
use Illuminate\Http\Request;

class ActivitylogController extends AdminBaseController
{
    use VerificationTrait;

    public function get_logs(Request $request)
    {
        $pagelog = Activities::get_log($request);
        return view('vendor.admin.activitylog.index', compact('pagelog'));
    }

    public function get_last_five_days_page_logs(Request $request)
    {
        $page_log = Pagelog::get_pagelog();

        return view('vendor.admin.pagelog.page_log', compact('page_log'));
    }

    public function payment_setting_edit()
    {
        $payment_setting = PaymentSetting::first();
        return view('vendor.admin.paymentSetting.payment_update', compact('payment_setting'));
    }

    public function payment_setting_update(Request $request)
    {
        $id = $request['payment_id'];
        $obj = PaymentSetting::find($id);
        $obj->Tap_enabled = $request['Tap_enabled'];
        $obj->Tap_mode = $request['Tap_mode'];
        $obj->secret_key = $request['secret_key'];
        $obj->update();
        flash('Changes successfully saved!')->info();
        return back();
    }

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

    public function Contact_Card_Problems()
    {

        $data = ContactCardProblems::orderBy('id', 'desc')->paginate(10);
        return view('vendor.admin.activitylog.contact_card_problems', compact('data'));
    }

    public function Contact_problem_delete($id)
    {
        if (!empty($id)) {
            ContactCardProblems::where('id', $id)->delete();
            return redirect()->back();
        }
    }

    public function Contact_multiple_delete(Request $request)
    {
        if (!empty($request->input())) {
            $id = explode(',', $request->input('contact_problems_ids'));
        } else {
            $id = [];
        }
        ContactCardProblems::whereIn('id', $id)->delete();
        flash('Deleted Successfully')->info();

        return redirect()->back();
    }

    public function ContactCards()
    {
        $limit = !empty(request()->get('limit')) ? request()->get('limit') : 50;
        $company_id = !empty(request()->get('company')) ? request()->get('company') : '';
        $data = Unlock::orderBy('created_at', 'desc');
        if (!empty($company_id)) {
            $data->where('to_user_id', $company_id);
        }
        $result = $data->paginate($limit);
        $companies = User::where('user_type_id', 1)->get();
        return view('vendor.admin.contact_cards')->with(['data' => $result, 'companies' => $companies]);
    }

    public function track_applicant()
    {
        $employer_id = request()->input('employer_id');
        $user_id = request()->input('user_id');

        $applied = Applicant::track_applicant_user_by_employer($user_id, $employer_id);
        return response()->json($applied);
    }
}
