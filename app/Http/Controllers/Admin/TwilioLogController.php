<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TwilioLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Helpers\Twilio;
use App\Models\UserSetting;

class TwilioLogController extends Controller
{
    public function index(Request $request)
    {
        $title = 'Twilio Logs';
        $breadcumbs = [
            [
                'title'=> 'Dashboard',
                'link'=> admin_url('dashboard')
            ],
            [
                'title'=> 'Twilio Logs',
                'link'=> 'javascript:void(0)'
            ]
        ];
        return view('admin.settings.twiliolog',compact('title','breadcumbs'));
    }

    public function ajax(Request $request)
    {
        $twiliolog = TwilioLog::get_twilio_log($request);
        $twiliolog_count = TwilioLog::get_twilio_log_count($request);
        $data = [];
        if ($twiliolog->count() > 0) {
            foreach ($twiliolog as $key => $log) {
                $start = $request->get('start', 0);
                $data[$key][] = $start + $key + 1;
                $row = '';
                if ($log->is_sent == 1) {
                    $row .= '<span class="btn btn-primary" style="cursor: text;">Sent</span>';
                } else {
                    $row .= '<span class="btn btn-success" onclick="change_message_status(' . $log->id . ')">Pending</span>';
                }
                $data[$key][] = $row;
                $data[$key][] = $log->user->name;
                $data[$key][] = $log->number;
                $data[$key][] = $log->response;
                $data[$key][] = Carbon::parse($log->created_at)->format('Y-m-d H:i:s');
                $counter = $key + 1;
                $data[$key][] = 
                '<div class="btn-group" role="group" aria-label="Action">
                    <div class="dropdown">
                        <div class="btn-group" role="group">
                            <button id="btnGroupDrop' . $counter . '" type="button" class="btn btn-sm btn-warning dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>
                            <div class="dropdown-menu" aria-labelledby="btnGroupDrop' . $counter . '">
                                <a class="dropdown-item" href="javascript:void(null)" onclick="view_message(' . $log->id . ')">Message Preview</a>
                                <a class="dropdown-item" href="javascript:void(null)" onclick="resend_message(' . $log->id . ')">Resend Message</a>
                            </div>
                        </div>
                    </div>
                </div>';
            }
        }

        return response()->json([
            'draw' => $request->get('draw'),
            'recordsTotal' => $twiliolog_count,
            'recordsFiltered' => $twiliolog_count,
            'data' => $data,
        ]);
    }

    public function viewMessage($id)
    {
        $twilioLog = TwilioLog::find($id);
        if (!$twilioLog) {
            return response()->json(['status' => 'error', 'message' => trans('Twilio message log entry not found in the system.')], 500);
        }
        if (!empty($twilioLog->message)) {
            return response()->json(['status' => 'success', 'data' => [
                'type' => $twilioLog->type,
                'message' => nl2br(e($twilioLog->message))
            ]]);
        } else {
            return response()->json(['status' => 'error', 'message' => trans('Unable to view message.')], 500);
        }
    }

    public function resendMessage(Request $request)
    {            
        $id = $request->input('id');
        $twilioLog = TwilioLog::find($id);
        
        if (!$twilioLog) {
            return response()->json(['status' => 'error', 'message' => trans('The requested message does not exist.')], 500);
        }

        $twilio = new Twilio();
        $to = 'whatsapp:' . $twilioLog->number;
        $messagecheck = $twilio->sendSMS($to,$twilioLog->message);
        if($messagecheck['status']){
            $twilioLog->is_sent = 1;
            $twilioLog->response = $messagecheck['message'];
            $twilioLog->save();
            $userSetting = UserSetting::where('user_id',$twilioLog->user_id)->first();
            $userSetting->update(['updated_at' => now()]);
            return response()->json(['status' => 'success', 'message' => trans('Message resent successfully.')]);
        }else{
            $twilioLog->is_sent = 0;
            $twilioLog->response = $messagecheck['message'];
            $twilioLog->save();
            return response()->json(['status' => 'error', 'message' => trans('Something went wrong while resending the message.')], 500);
        }
    }

    public function changeMessageStatus($id)
    {
        $twilioLog = TwilioLog::find($id);
        if (!$twilioLog) {
            return response()->json(['status' => 'error', 'message' => trans('Message log entry not found in the system.')], 500);
        }

        $twilioStatus['is_sent'] = 1;
        $twilioLog->update($twilioStatus);
            
        return response()->json(['status' => 'success', 'message' => trans('The status has been changed to Sent.')]);        
    }
}
