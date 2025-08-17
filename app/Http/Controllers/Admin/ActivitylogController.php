<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Auth\Traits\VerificationTrait;
use App\Models\Activities;
use App\Models\Applicant;
use App\Models\Company;
use App\Models\ContactCardProblems;
use App\Models\Pagelog;
use App\Models\PaymentSetting;
use App\Models\Unlock;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class ActivitylogController extends AdminBaseController
{
    use VerificationTrait;

    public function get_logs(Request $request)
    {
        $title = 'Activity Logs';
        $breadcumbs = [
            [
                'title'=> 'Dashboard',
                'link'=> admin_url('dashboard')
            ],
            [
                'title'=> 'Activity Logs',
                'link'=> 'javascript:void(0)'
            ]
        ];
        return view('admin.settings.activitylog',compact('title','breadcumbs'));
    }

    public function ajax(Request $request)
    {
        $pagelog = Activities::get_log($request);
        $pagelog_count = Activities::get_log_count($request);
        $data = [];
        if ($pagelog->count() > 0) {
            foreach ($pagelog as $log) {
                $url = '';
                $replace_text = '';
                if(!empty($log->user->name)){
                    if($log->user->user_type_id == 1){
                        $url = admin_url() . '/employer?search=' . $log->user->email;
                    }elseif($log->user->user_type_id == 5){
                        $url = admin_url() . '/affiliates?search=' . $log->user->email;
                    }else{
                        $url = admin_url() . '/job-seekers?search=' . $log->user->email;
                    }
                    
                    $replace_text = '<a href="'.$url.'" target="_blank">'.$log->user->name.'</a>';
                    $description = $result = str_replace("{{company_name}}", $replace_text, $log->description);
                }else{
                    $description = $log->description;
                }
                
                $data[] = [
                    'id' => $log->id,
                    'created_at' => Carbon::parse($log->created_at)->format('Y-m-d H:i:s'),
                    'description' => $description,
                    'user_id' => $log->user_id, // Send user_id for conditional styling
                ];
            }
        }

        return response()->json([
            'draw' => $request->get('draw'),
            'recordsTotal' => $pagelog_count,
            'recordsFiltered' => $pagelog_count,
            'data' => $data,
        ]);
    }

    public function get_last_five_days_page_logs(Request $request)
    {
        $page_log = Pagelog::get_pagelog();

        return view('vendor.admin.pagelog.page_log', compact('page_log'));
    }

    public function payment_setting_edit()
    {
        $payment_setting = PaymentSetting::first();
        $title = 'Payment Settings';
        $breadcumbs = [
            [
                'title'=> 'Dashboard',
                'link'=> admin_url('dashboard')
            ],
            [
                'title'=> 'Payment Settings',
                'link'=> 'javascript:void(0)'
            ]
        ];
        return view('admin.settings.payment_update', compact('payment_setting','title','breadcumbs'));
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


    public function Contact_Card_Problems()
    {
        $title = 'Contact Card Problems';
        $breadcumbs = [
            [
                'title'=> 'Dashboard',
                'link'=> admin_url('dashboard')
            ],
            [
                'title'=> 'Contact Card Problems',
                'link'=> 'javascript:void(0)'
            ]
        ];
        return view('admin.settings.contact_card_problems',compact('title','breadcumbs'));
    }

    public function Contact_Card_Problems_ajax(Request $request)
    {

        $contact_card_problem = ContactCardProblems::orderBy('id', 'desc')->paginate(10);
        $contact_card_problem_count = ContactCardProblems::count();
        $data = [];

        $i = 1;
        foreach ($contact_card_problem as $key => $item) {
            $job_seeker_url = admin_url() . '/job-seekers?search=' . $item->name;
            $employer = admin_url() . '/employer?search=' . $item->company;
            $data[$key][] = '<td><input type="checkbox" name="contact_problems_ids" class="checkbox" value="'.$item->id.'"></td>';
            $counter = $key+1;
            $data[$key][] = '<td>'.$counter.'</td>';
            $data[$key][] = '<td><a href="'.$job_seeker_url.'">'.$item->name.'</a></td>';
            $data[$key][] = '<td><a href="'.$employer.'">'.$item->company.'</a></td>';
            $data[$key][] = '<td>'.date('d-M-Y h:i a', strtotime($item->created_at)).'</td>';
            $data[$key][] = '<td>
                <div class="btn-group" role="group">
                    <button id="btnGroupDrop1" type="button" class="btn btn-secondary dropdown-toggle btn-sm" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>
                    <div class="dropdown-menu" aria-labelledby="btnGroupDrop1" style="">
                        <a href="'.URL('admin/contactdeleteproblem/' . $item->id).'" class="dropdown-item delete-link"><i class="fa fa-trash"></i>'.trans('admin.delete').'</a>
                    </div>
                </div>
            </td>';
        }

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(
            ['draw' => $request->get('draw'),
                'recordsTotal' => $contact_card_problem_count,
                'recordsFiltered' =>  $contact_card_problem_count,
                'data' => $data,
            ]
        );
        die;
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
    
    public function get_company_filter_data(Request $request)
    {
        $companies = Cache::remember('active_companies', 60, function () {
            return Company::get_companies();
        });

        $data = [
            'companies' => $companies,
        ];
        $response = array(
            'status' => true,
            'data' => $data,
        );
        return response()->json($response);
    }
}
