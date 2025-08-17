<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\MailGunHelper;
use App\Http\Controllers\Controller;
use App\Models\EmailQueue;
use Carbon\Carbon;
use Illuminate\Http\Request;

class EmailLogController extends AdminBaseController
{
    public function index(Request $request)
    {
        $title = 'Email Logs';
        $breadcumbs = [
            [
                'title'=> 'Dashboard',
                'link'=> admin_url('dashboard')
            ],
            [
                'title'=> 'Email Logs',
                'link'=> 'javascript:void(0)'
            ]
        ];
        return view('admin.settings.emaillog',compact('title','breadcumbs'));
    }

    public function ajax(Request $request)
    {
        $emaillog = EmailQueue::get_email_log($request);
        $emaillog_count = EmailQueue::get_email_log_count($request);
        $data = [];
        if ($emaillog->count() > 0) {
            foreach ($emaillog as $key => $log) {
                $data[$key][] = $log->id;
                $row = '';
                if ($log->status == 1) {
                    $row .= '<span class="btn btn-primary" onclick="change_email_status(' . $log->id . ')">Pending</span>';
                } else {
                    $row .= '<span class="btn btn-success" style="cursor: text;">Sent</span>';
                }
                $data[$key][] = $row;
                $data[$key][] = $log->to;
                $data[$key][] = $log->subject;
                $data[$key][] = Carbon::parse($log->created_at)->format('Y-m-d H:i:s');
                // $data[$key][] = Carbon::parse($log->updated_at)->format('Y-m-d H:i:s');
                $counter = $key + 1;
                $data[$key][] = 
                '<div class="btn-group" role="group" aria-label="Action">
                    <div class="dropdown">
                        <div class="btn-group" role="group">
                            <button id="btnGroupDrop' . $counter . '" type="button" class="btn btn-sm btn-warning dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>
                            <div class="dropdown-menu" aria-labelledby="btnGroupDrop' . $counter . '">
                                <a class="dropdown-item" href="javascript:void(null)" onclick="view_email(' . $log->id . ')">Email Preview</a>
                                <a class="dropdown-item" href="javascript:void(null)" onclick="resend_email(' . $log->id . ')">Resend Email</a>
                            </div>
                        </div>
                    </div>
                </div>';
            }
        }

        return response()->json([
            'draw' => $request->get('draw'),
            'recordsTotal' => $emaillog_count,
            'recordsFiltered' => $emaillog_count,
            'data' => $data,
        ]);
    }

    public function viewEmail($id)
    {
        $emailQueue = EmailQueue::find($id);
        if (!$emailQueue) {
            return response()->json(['status' => 'error', 'message' => trans('Email queue entry not found in the system.')], 500);
        }
        if (!empty($emailQueue->body)) {
            return response()->json(['status' => 'success', 'data' => $emailQueue]);
        } else {
            return response()->json(['status' => 'error', 'message' => trans('Unable to view email.')], 500);
        }
    }

    public function resendEmail(Request $request)
    {            
        $id = $request->input('id');
        $email_queue = EmailQueue::find($id);
        
        if (!$email_queue) {
            return response()->json(['status' => 'error', 'message' => trans('The requested email does not exist.')], 500);
        }

        $send = MailGunHelper::send_mail_with_mailgun($email_queue);

        if($send){
            return response()->json(['status' => 'success', 'message' => trans('Email resent successfully.')]);
        }else{
            return response()->json(['status' => 'error', 'message' => trans('Something went wrong while resending the email.')], 500);
        }

    }

    public function changeEmailStatus($id)
    {
        $emailQueue = EmailQueue::find($id);
        if (!$emailQueue) {
            return response()->json(['status' => 'error', 'message' => trans('Email queue entry not found in the system.')], 500);
        }

        $email_qu['status'] = 2;
        $emailQueue->update($email_qu);
            
        return response()->json(['status' => 'success', 'message' => trans('The status has been changed to Sent.')]);        
    }
}
