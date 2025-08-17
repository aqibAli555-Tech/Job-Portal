<?php

namespace App\Http\Controllers\Affiliate;

use App\Helpers\EmailHelper;
use App\Helpers\Helper;
use App\Models\ReferralCommission;
use App\Models\WithdrawRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;
use Str;


class WithdrawRequestController extends AffiliateBaseController
{
    public function index()
    {
        if(!auth()->check()){
            return redirect()->back();
        }

        view()->share([
            'title' => t('withdraw_requests'),
            'description' => t('withdraw_requests'),
            'keywords' => t('withdraw_requests'),
        ]);

        return view('affiliate.withdraw_requests.index');
    }

    public function ajax(Request $request)
    {
        $data = [];
        $user_id = auth()->user()->id;
        $withdraw_requests = WithdrawRequest::with('commission')->where('user_id', $user_id)->orderBy('id', 'DESC');

        if (!empty($request->get('month'))) {
            $month = $request->get('month');
            $withdraw_requests = $withdraw_requests->whereHas('commission', function ($query) use ($month) {
                $query->where('month', $month);
            });
        }
        
        if (!empty($request->get('year'))) {
            $year = $request->get('year');
            $withdraw_requests = $withdraw_requests->whereHas('commission', function ($query) use ($year) {
                $query->where('year', $year);
            });
        }

        if (!empty($request->get('filter_status'))) {
            $withdraw_requests = $withdraw_requests->where('status',$request->get('filter_status'));
        }

        $recordsFiltered = $withdraw_requests->count();
        $recordsTotal = $withdraw_requests->count();
        $withdraw_requests = $withdraw_requests->get();

        if ($withdraw_requests->count() > 0) {
            foreach ($withdraw_requests as $key => $withdraw_request) {

                $rejected_reason = '';
                if($withdraw_request->status === 'rejected' && !empty($withdraw_request->rejected_reason)){
                    $rejected_reason = '<br><strong>Rejected Reason</strong> : '. $withdraw_request->rejected_reason;
                }

                $commission_slot = json_decode($withdraw_request->commission->commission_slot, true);
                $slot_text = '';
                
                if(!empty($commission_slot)){
                    $slot_text = '<br><strong>Slot : </strong>$' . number_format($commission_slot['min_amount'])   . ' - $' . number_format($commission_slot['max_amount']) . ' => ' . $commission_slot['commission'] . '%'; 
                }
                
                $data[$key][] = '<div class="bg-transparent shadow-none flex-row flex-wrap m-0"><div class="card-block px-2"><p class="card-text"><strong>Date : </strong>' . $withdraw_request->commission->month . ' ' . $withdraw_request->commission->year . '<br><strong>Total Revenue : </strong>$' . $withdraw_request->commission->total_revenue . '<br><strong>Commission Amount : </strong>$' . $withdraw_request->amount . $slot_text 
                . '<br><strong>Apply Fee : </strong>' . $withdraw_request->commission->apply_fee_type . '%<br><strong>Apply Fee Amount : </strong>$' . $withdraw_request->commission->apply_fee_amount . 
                '<br><strong>Commission After Apply Fee : </strong>$' . $withdraw_request->commission->commission_after_apply_fee . '</p></div></div>';               
                $row = '';
                
                if ($withdraw_request->status === 'approved') {
                    $row .= '<span class="badge badge-success">' . Helper::withdraw_request_status('approved') . '</span>';
                } elseif($withdraw_request->status === 'requested') {
                    $row .= '<span class="badge badge-secondary">' . Helper::withdraw_request_status('requested') . '</span>';
                }  elseif($withdraw_request->status === 'rejected') {
                    $row .= '<span class="badge badge-danger">' . Helper::withdraw_request_status('rejected') . '</span>'. $rejected_reason;
                }elseif($withdraw_request->status === 'paypal_requested') {
                    $row .= '<span class="badge badge-dark">' . Helper::withdraw_request_status('paypal_requested') . '</span>';
                }  else {
                    $row .= '<span class="badge badge-primary">' . Helper::withdraw_request_status('processing') . '</span>';
                }
                
                $data[$key][] = $row;
                // $delete_request = '';
                
                // if ($withdraw_request->status === 'processing') {
                //     $delete_request = '<a class="btn btn-danger btn-sm" onclick="delete_withdraw_request('.$withdraw_request->id.')" href="javascript:void(null)"><i class="fa fa-trash" aria-hidden="true"></i>&nbsp&nbsp;Delete</a>';
                // }
                
                // $data[$key][] =
                //     '<div class="btn-group" role="group" aria-label="Action">
                //             '. $delete_request .'
                //     </div>';
            }
        }

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(
            [   'draw' => $request->get('draw'),
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsFiltered,
                'data' => $data]);
        die;
    }

    public function deleteRequest($id)
    {
        $withdraw_request = WithdrawRequest::find($id);

        if (!$withdraw_request) {
            return response()->json(['success' => false, 'msg' => 'Request not found.']);
        }
        $referral_commission = ReferralCommission::find($withdraw_request->referral_commission_id);
        if ($referral_commission) {
            $referral_commission->update(['status' => 'pending']);
        }        
        $withdraw_request->update([
            'status' => 'rejected',
            'rejected_reason' => 'User has been delete the request',
        ]);
        
        return response()->json(['success' => true, 'msg' => 'Request deleted successfully.']);
    }
    
}
