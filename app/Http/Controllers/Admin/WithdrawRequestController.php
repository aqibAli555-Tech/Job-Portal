<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\ReferralCommission;
use App\Models\WithdrawRequest;
use Illuminate\Http\Request;
use App\Helpers\PayPal;
use App\Models\AffiliateBankDetail;
use App\Models\PaypalLog;

class WithdrawRequestController extends AdminBaseController
{
    public function index(Request $request)
    {
        WithdrawRequest::markAsSeen();
        $title = 'Withdraw Requests';
        $breadcumbs = [
            [
                'title' => 'Dashboard',
                'link' => admin_url('dashboard')
            ],
            [
                'title' => 'Withdraw Requests',
                'link' => 'javascript:void(0)'
            ]
        ];

        return view('admin.withdraw-requests.index', compact('title', 'breadcumbs'));
    }

    public function ajax(Request $request){
        $data = [];
        $withdraw_requests = WithdrawRequest::get_withdraw_requests($request);
        $filtered = WithdrawRequest::get_withdraw_requests_filter_count($request);
        $withdraw_requests_count = WithdrawRequest::get_withdraw_requests_count();
        if ($withdraw_requests->count() > 0){
            foreach ($withdraw_requests as $key => $withdraw_request){
                $data[$key][] = '<div class="card bg-transparent shadow-none flex-row flex-wrap m-0"><div class="card-header border-0 p-0"><img width="55" height="55" src="' . Helper::getImageOrThumbnailLink($withdraw_request->user) . '" alt=""></div><div class="card-block px-2"><p class="card-text"><strong><span class="badge badge-success"># ' . $withdraw_request->user->id . '</span> &nbsp;' . $withdraw_request->user->name . '</strong><br>' . $withdraw_request->user->email . '<br>'. $withdraw_request->user->phone .'<br><img height="20" alt="'.$withdraw_request->user->country_code.'" src="' . url()->asset('images/flags/16/' . strtolower($withdraw_request->user->country_code) . '.png') . '"/>&nbsp;<br>'. date('d M-Y h:i A', strtotime($withdraw_request->user->created_at)) . '</p></div>     </div>';
                $rejected_reason = '';
                if($withdraw_request->status === 'rejected' && !empty($withdraw_request->rejected_reason)){
                    $rejected_reason = '<br><strong>Rejected Reason</strong> : '. $withdraw_request->rejected_reason;
                }
                $commission_slot = json_decode($withdraw_request->commission->commission_slot, true);
                $slot_text = '';
                if(!empty($commission_slot)){
                    $slot_text = '<br><strong>Slot : </strong>$' . number_format($commission_slot['min_amount'])   . ' - $' . number_format($commission_slot['max_amount']) . ' => ' . $commission_slot['commission'] . '%'; 
                }  
                $data[$key][] = '<div class="bg-transparent shadow-none flex-row flex-wrap m-0"><div class="card-block px-2"><p class="card-text"><strong>Date : </strong>' . $withdraw_request->commission->month . ' ' . $withdraw_request->commission->year . '<br><strong>Total Revenue : </strong>$' . 
                $withdraw_request->commission->total_revenue . '<br><strong>Commission Amount : </strong>$' . $withdraw_request->amount . $slot_text . '<br><strong>Apply Fee : </strong>' . $withdraw_request->commission->apply_fee_type . '%<br><strong>Apply Fee Amount : </strong>$' . $withdraw_request->commission->apply_fee_amount . 
                '<br><strong>Commission After Apply Fee : </strong>$' . $withdraw_request->commission->commission_after_apply_fee . '</p></div></div>';
                $row = '';
                if ($withdraw_request->status === 'approved') {
                    $row .= '<span class="btn btn-success" style="cursor: text;">' . Helper::withdraw_request_status('approved') . '</span>';
                } elseif($withdraw_request->status === 'requested') {
                    $row .= '<span class="btn btn-secondary" style="cursor: text;">' . Helper::withdraw_request_status('requested') . '</span>';
                }  elseif($withdraw_request->status === 'rejected') {
                    $row .= '<span class="btn btn-danger" style="cursor: text;">' . Helper::withdraw_request_status('rejected') . '</span>' . $rejected_reason;
                }  elseif($withdraw_request->status === 'paypal_requested') {
                    $row .= '<span class="btn btn-dark" style="cursor: text;">' . Helper::withdraw_request_status('paypal_requested') . '</span>';
                }  else {
                    $row .= '<span class="btn btn-primary" style="cursor: text;">' . Helper::withdraw_request_status('processing') . '</span>';
                }
                $data[$key][] = $row;                
                $counter = $key + 1;
                $data[$key][] = ($withdraw_request->status === 'requested' || $withdraw_request->status === 'paypal_requested') ?
                '<div class="btn-group" role="group" aria-label="Action">
                    <div class="dropdown">
                        <div class="btn-group" role="group">
                            <button id="btnGroupDrop' . $counter . '" type="button" class="btn btn-sm btn-warning dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>
                            <div class="dropdown-menu" aria-labelledby="btnGroupDrop' . $counter . '">'
                            . ($withdraw_request->status === 'requested' ? 
                                '<a class="dropdown-item" href="javascript:void(null)" onclick="request_to_paypal(' . $withdraw_request->id . ')"><i class="fas fa-hand-holding-usd"></i> Request To Paypal</a>'
                            : '')
                            . ($withdraw_request->status === 'paypal_requested' ? 
                                '<a class="dropdown-item" href="javascript:void(null)" onclick="view_payout(' . $withdraw_request->id . ')"><i class="fas fa-eye"></i> View Request</a>'
                            : '')
                            . '</div>
                        </div>
                    </div>
                </div>' : '';
            
            }
        }
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(
            ['draw' => $request->get('draw'),
                'recordsTotal' => $withdraw_requests_count,
                'recordsFiltered' =>  $filtered,
                'data' => $data]);
        die;
    }

    public function withdraw_request_status_change(Request $request)
    {            
        $id = $request->input('id');
        $withdraw_request = WithdrawRequest::find($id);
        
        if (!$withdraw_request) {
            flash(trans('The requested withdrawal does not exist.'))->error();
            return redirect()->back();
        }
        $data['name'] = $withdraw_request->user->name;
        $data['referrer_url'] = admin_url() . '/affiliates?search=' . $withdraw_request->user->email;
        $data['amount'] = $withdraw_request->amount;

        if($request->input('status') === 'rejected' && !empty($request->input('rejected_reason'))){
            $withdraw_request->status = $request->input('status');
            $withdraw_request->rejected_reason = $request->input('rejected_reason');
            $withdraw_request->save();
            $referralCommission = ReferralCommission::find($withdraw_request->referral_commission_id);
            $referralCommission->update(['status' => 'pending']);

            $data['status'] = $request->input('status');
            $affiliateDescription = Helper::adminAffiliateActivityLogDescription($data, 'change_withdraw_request_status');
            if(!empty($affiliateDescription)){
                Helper::activity_log($affiliateDescription);
            }

        }else if($request->input('status') === 'approved'){
            
            if ($withdraw_request->referral_commission_id) {
                $referralCommission = ReferralCommission::find($withdraw_request->referral_commission_id);
                
                if(!empty($referralCommission)){
                    $referralCommission->status = 'paid';
                    $referralCommission->save();
                    $withdraw_request->status = $request->input('status');
                    $withdraw_request->save();

                    $data['status'] = $request->input('status');
                    $affiliateDescription = Helper::adminAffiliateActivityLogDescription($data, 'change_withdraw_request_status');
                    if(!empty($affiliateDescription)){
                        Helper::activity_log($affiliateDescription);
                    }

                    return response()->json(['status' => 'success', 'message' => trans('Withdrawal request has been successfully approved.')]);
                    
                }else{
                    return response()->json(['status' => 'error', 'message' => trans('No referral commission available for this withdrawal request.')], 500);
                }
            }else{
                return response()->json(['status' => 'error', 'message' => trans('No referral commission is linked to this withdrawal request.')], 500);
            }
        }else if($request->input('status') === 'paypal_requested'){
            
            if ($withdraw_request->referral_commission_id) {
                $referralCommission = ReferralCommission::find($withdraw_request->referral_commission_id);
                
                if(!empty($referralCommission)){
                    $bankDetail = AffiliateBankDetail::where('user_id',$withdraw_request->user_id)->first();
                    
                    if(empty($bankDetail) || empty($bankDetail->email)){
                        return response()->json(['status' => 'error', 'message' => trans('No PayPal email is associated with your account for withdrawals.')], 400);
                    }
                    $receiverEmail = $bankDetail->email;
                    $withdrawData =  $withdraw_request;
        
                    $paypalResponse = PayPal::sendPayout($receiverEmail, $withdrawData);
                    if ($paypalResponse['success']) {
                        $withdraw_request->status = $request->input('status');
                        $withdraw_request->save();

                        $data['status'] = 'requested PayPal to process';
                        $affiliateDescription = Helper::adminAffiliateActivityLogDescription($data, 'change_withdraw_request_status');
                        if(!empty($affiliateDescription)){
                            Helper::activity_log($affiliateDescription);
                        }

                        return response()->json(['status' => 'success', 'message' => trans('PayPal request has been successfully sent.')]);
                    } else {
                        return response()->json(['status' => 'error', 'message' => $paypalResponse['message'] ?? trans('There was a problem sending the request to PayPal.')], 500);
                    }
                }else{
                    return response()->json(['status' => 'error', 'message' => trans('No referral commission available for this withdrawal request.')], 500);
                }
            }else{
                return response()->json(['status' => 'error', 'message' => trans('No referral commission is linked to this withdrawal request.')], 500);
            }
        }else{
            flash(trans('Unable to process your withdrawal request update.'))->error();
        }
        return redirect()->back();
    }

    public function getUnseenWithdrawRequest()
    {
        $result = WithdrawRequest::getUnseenRequests();
        return response()->json($result, 200, [], JSON_UNESCAPED_UNICODE);

    }

    public static function checkPayoutStatus($id)
    {
        $paypalLog = PaypalLog::where('withdraw_request_id',$id)->first();
        if (!$paypalLog) {
            return response()->json(['status' => 'error', 'message' => trans('PayPal payout entry not found in the system.')], 500);
        }

        $paypalResponse = PayPal::checkPayoutStatus($paypalLog->paypal_id);
        if ($paypalResponse['success']) {
            return response()->json(['status' => 'success', 'data' => $paypalResponse['data']]);
        } else {
            return response()->json(['status' => 'error', 'message' => $paypalResponse['message'] ?? trans('Unable to check PayPal payout status.')], 500);
        }
    }
}
