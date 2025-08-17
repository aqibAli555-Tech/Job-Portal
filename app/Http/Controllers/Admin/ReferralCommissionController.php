<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\ReferralCommission;
use App\Models\User;
use Illuminate\Http\Request;

class ReferralCommissionController extends Controller
{
    public function index(Request $request)
   {
       $data['referrers'] = User::where('user_type_id', 5)->get();
       $title = trans('admin.referral_commission');
       $breadcumbs = [
           [
               'title'=> 'Dashboard',
               'link'=> admin_url('dashboard')
           ],
           [
               'title'=> 'Referral Commission',
               'link'=> 'javascript:void(0)'
           ]
       ];
       return view('admin.referral-commission.index', compact('title','breadcumbs','data'));
   }

    public function ajax(Request $request)
    {
        $data = [];
        $referral_commissions = ReferralCommission::get_referral_commissions($request);
        $filtered = ReferralCommission::get_referral_commission_filter_count($request);
        $referral_commission_count = ReferralCommission::get_referral_commission_count();
        if ($referral_commissions->count() > 0){
            foreach ($referral_commissions as $key => $referral_commission){
                $data[$key][] = '<td class="d-flex flex-column border-0"><div class="pt-1 text-center"><input type="checkbox" name="commission_ids" class="checkbox" onclick="SingletoggleCheckbox(this)" value="' . $referral_commission->id . '"></div></td>';
                $data[$key][] = '<div class="card bg-transparent shadow-none flex-row flex-wrap m-0"><div class="card-header border-0 p-0"><img width="55" height="55" src="' . Helper::getImageOrThumbnailLink($referral_commission->affiliateUser) . '" alt=""></div><div class="card-block px-2"><p class="card-text"><strong><span class="badge badge-success"># ' . $referral_commission->affiliateUser->id . '</span> &nbsp;' . $referral_commission->affiliateUser->name . '</strong><br>' . $referral_commission->affiliateUser->email . '<br>'. $referral_commission->affiliateUser->phone .'<br><img height="20" alt="'.$referral_commission->affiliateUser->country_code.'" src="' . url()->asset('images/flags/16/' . strtolower($referral_commission->affiliateUser->country_code) . '.png') . '"/>&nbsp;<br>'. date('d M-Y h:i A', strtotime($referral_commission->affiliateUser->created_at)) . '</p></div></div>';
                $commission_slot = json_decode($referral_commission->commission_slot, true);
                $slot_text = '';
                if(!empty($commission_slot)){
                    $slot_text = '<br><strong>Slot : </strong>$' . number_format($commission_slot['min_amount'])   . ' - $' . number_format($commission_slot['max_amount']) . ' => ' . $commission_slot['commission'] . '%'; 
                }
                $data[$key][] = '<div class="bg-transparent shadow-none flex-row flex-wrap m-0"><div class="card-block px-2"><p class="card-text"><strong>Date : </strong>' . $referral_commission->month . ' ' . $referral_commission->year . '<br><strong>Total Revenue : </strong>$' . $referral_commission->total_revenue . '<br><strong>Commission Amount : </strong>$' . $referral_commission->total_commission . $slot_text . '</p></div></div>';
                $row = '';
                if ($referral_commission->status === 'pending') {
                    $row .= '<span class="btn btn-primary" style="cursor: text;">' . Helper::referral_commission_status('pending') . '</span>';
                } elseif($referral_commission->status === 'paid') {
                    $row .= '<span class="btn btn-success" style="cursor: text;">' . Helper::referral_commission_status('paid') . '</span>';
                }  elseif($referral_commission->status === 'withdraw_request') {
                    $row .= '<span class="btn btn-secondary" style="cursor: text;">' . Helper::referral_commission_status('withdraw_request') . '</span>';
                } elseif($referral_commission->status === 'verification_inprocess') {
                    $row .= '<span class="btn btn-warning" style="cursor: text;">' . Helper::referral_commission_status('verification_inprocess') . '</span>';
                }
                $data[$key][] = $row;
            }
        }
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(
            ['draw' => $request->get('draw'),
                'recordsTotal' => $referral_commission_count,
                'recordsFiltered' =>  $filtered,
                'data' => $data]);
        die;
    }
}