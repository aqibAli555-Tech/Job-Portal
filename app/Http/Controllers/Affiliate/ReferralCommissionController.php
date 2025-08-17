<?php

namespace App\Http\Controllers\Affiliate;

use App\Helpers\Helper;
use App\Helpers\EmailHelper;
use App\Models\AffiliateBankDetail;
use App\Models\ReferralCommission;
use App\Models\WithdrawRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Str;

class ReferralCommissionController extends AffiliateBaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        if(!auth()->check()){
            return redirect('/');
        }

        view()->share([
            'title' => t('Commission'),
            'description' => t('Commission'),
            'keywords' => t('Commission'),
        ]);

        return view('affiliate.commission.index');
    }

    public function ajax(Request $request){

        $data = [];
        $id = auth()->user()->id;
        $commissions = ReferralCommission::where('affiliate_id',$id)->orderBy('id', 'DESC');
        
        if (!empty($request->get('month'))) {
            $month = $request->get('month');
            $commissions = $commissions->where(function ($query) use ($month) {
                $query->where('month', 'LIKE', "%{$month}%");
            });
        }
        
        if (!empty($request->get('year'))) {
            $year = $request->get('year');
            $commissions = $commissions->where(function ($query) use ($year) {
                $query->where('year', 'LIKE', "%{$year}%");
            });
        }

        if (!empty($request->get('status'))) {
            $commissions = $commissions->where('status',$request->get('status'));
        }
        
        $recordsFiltered = $commissions->count();
        $recordsTotal = $commissions->count();
        $commissions = $commissions->get();

        if ($commissions->count() > 0) {
            foreach ($commissions as $key => $commission) {
                
                $commission_slot = json_decode($commission->commission_slot, true);
                $slot_text = '';
                
                if(!empty($commission_slot)){
                    $slot_text = '<br><strong>Tier Slot : </strong>$' . number_format($commission_slot['min_amount'])   . ' - $' . number_format($commission_slot['max_amount']) . ' => ' . $commission_slot['commission'] . '%'; 
                }
                
                $data[$key][] = '<div class="bg-transparent shadow-none flex-row flex-wrap m-0"><div class="card-block px-2"><p class="card-text"><strong>Date : </strong>' . $commission->month . ' ' . $commission->year . '<br><strong>Total Revenue : </strong>$' . $commission->total_revenue . '<br><strong>Commission Amount : </strong>$' . $commission->total_commission . $slot_text . '</p></div></div>';
                
                $row = '';
                if($commission->status === 'pending') {
                    $row .= '<span class="badge badge-primary">' . Helper::referral_commission_status('pending') . '</span>';
                } elseif ($commission->status === 'paid') {
                    $row .= '<span class="badge badge-success">' . Helper::referral_commission_status('paid') . '</span>';
                } elseif($commission->status === 'withdraw_request') {
                    $row .= '<span class="badge badge-secondary">' . Helper::referral_commission_status('withdraw_request') . '</span>';
                } elseif($commission->status === 'verification_inprocess') {
                    $row .= '<span class="badge badge-warning">' . Helper::referral_commission_status('verification_inprocess') . '</span>';
                }
                
                $data[$key][] = $row;
                $currentMonth = now()->format('F');;
                $currentYear = now()->format('Y');
                $lastMonth = now()->subMonth()->format('F');
                $lastYear = now()->subMonth()->format('Y');
                $bankDetail = AffiliateBankDetail::where('user_id',$id)->first();
                $monthNumbers = [
                    "January" => 1,
                    "February" => 2,
                    "March" => 3,
                    "April" => 4,
                    "May" => 5,
                    "June" => 6,
                    "July" => 7,
                    "August" => 8,
                    "September" => 9,
                    "October" => 10,
                    "November" => 11,
                    "December" => 12
                ];
                if ($commission->status === 'pending' && $bankDetail && 
                    ($commission->month != $lastMonth || $commission->year != $lastYear) &&
                    ($commission->year < $currentYear || ($commission->year == $currentYear && $monthNumbers[$commission->month] < $monthNumbers[$currentMonth]))
                    ) 
                {
                    $url = url('affiliate/commission-withdraw-request/' . $commission->id);
                    $data[$key][] =
                    '<div class="btn-group" role="group" aria-label="Action">
                        <a class="btn btn-primary btn-sm" href="' . $url . '"><i class="fa fa-envelope-open" aria-hidden="true"></i>&nbsp;Withdraw Request</a>
                    </div>';
                }else{
                    $bankDetailUrl = url('affiliate/bank_details');
                    if(!$bankDetail){
                        $data[$key][] =
                        '<div class="btn-group" role="group" aria-label="Action">
                            <a class="btn btn-primary btn-sm" href="' . $bankDetailUrl . '"><i class="fa fa-bank" aria-hidden="true"></i>&nbsp;Bank Detail</a>
                        </div>';
                    }else{
                        $data[$key][] = '';
                    }
                }
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

    public function commission_withdraw_request($id)
    {
        $userId = auth()->id();
        $referralCommission = ReferralCommission::where('id', $id)
            ->where('affiliate_id', $userId)
            ->first();

        if (!$referralCommission) {
            flash('Commission Not Found')->error();
            return redirect()->back();
        }

        $token = Str::random(60);
        $expiryTime = now()->addMinutes(30);

        $user_url = admin_url() . '/affiliates?search=' . auth()->user()->email;
        $name = auth()->user()->name;
        $admin_url = admin_url() . '/employer?search=contact@hungryforjobs.com';
        $url = "<a href='$admin_url'><strong>HungryForJobs</strong></a>";
        $description = "An affiliate User Name: <b> <a href='$user_url'>$name</a></b> has withdraw request of $" . $referralCommission->total_commission . " to Admin: ". $url ." at " .date('Y-m-d H:i:s');
        Helper::activity_log($description);
        $affiliatedata['amount'] =  $referralCommission->toatl_commission;
        $affiliateDescription = Helper::affiliateDescriptionData($affiliatedata, 'withdraw_request');
        if(!empty($affiliateDescription)){
            Helper::activity_log($affiliateDescription,auth()->user()->id);
        }

        $withdrawRequest = WithdrawRequest::create([
            'amount' => $referralCommission->total_commission,
            'user_id' => $userId,
            'referral_commission_id' => $id,
            'token' => $token,
            'status' => 'requested',
            'expiry_time' => $expiryTime,
        ]);

        $referralCommission->update(
            [
                'status' => 'withdraw_request',
            ]
        );

        flash(t('withdraw_request_email_send_successfully'))->success();

        return redirect()->back();
    }

}
