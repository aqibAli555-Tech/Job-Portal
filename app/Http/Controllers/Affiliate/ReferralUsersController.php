<?php

namespace App\Http\Controllers\Affiliate;

use App\Helpers\Helper;
use App\Models\Package;
use App\Models\Payment;
use App\Models\ReferralCommission;
use App\Models\User;
use Illuminate\Http\Request;

class ReferralUsersController extends AffiliateBaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(Request $request)
    {
        if(!auth()->check()){
            return redirect()->back();
        }
        view()->share([
            'title' => t('referral_users'),
            'description' => t('referral_users'),
            'keywords' => t('referral_users'),
        ]);

        $affiliates = User::where('user_type_id',5)->where('affiliate_id',auth()->user()->id)->get();
        $affiliate_id = (!empty($request->affiliate_id))?$request->affiliate_id:'';
        return view('affiliate.referrals.index',compact('affiliates','affiliate_id'));
    }

    public function ajax(Request $request)
    {
        $data = [];
        $affiliate_id = auth()->user()->id;
        $referral_affiliate_ids = User::where(['affiliate_id'=> $affiliate_id, 'user_type_id'=> 5])->where('deleted_at', NULL)->pluck('id');
        // $referral_users = User::where(['affiliate_id'=> $affiliate_id, 'user_type_id'=> 1])->where('deleted_at', NULL)->orderBy('id', 'DESC');

        $referral_users = User::where('user_type_id', 1)->where('affiliate_id', $affiliate_id)->orWhereIn('affiliate_id', $referral_affiliate_ids)->where('deleted_at', NULL)->orderBy('id', 'DESC');

        if (!empty($request->get('referral_affiliate_id'))) {
            $referral_affiliate_id = $request->get('referral_affiliate_id');

            if ($referral_affiliate_id == 'referred_by_me') {
                $referral_users = User::where([
                    'affiliate_id' => $affiliate_id,
                    'user_type_id' => 1
                ])->whereNull('deleted_at')->orderBy('id', 'DESC');
            } else {
                $referral_users = User::where([
                    'affiliate_id' => $referral_affiliate_id,
                    'user_type_id' => 1
                ])->whereNull('deleted_at')->orderBy('id', 'DESC');
            }
        }

        if (!empty($request->get('search_new'))) {
            $search = $request->get('search_new');
            $referral_users = $referral_users->where(function ($query) use ($search) {
                $query->where('name', 'LIKE', "%{$search}%")
                ->orwhere('email', 'LIKE', "%{$search}%")
                ->orwhere('phone', 'LIKE', "%{$search}%");
            });
        }

        $recordsFiltered = $referral_users->count();
        $recordsTotal = $referral_users->count();
        $referral_users = $referral_users->get();

        if ($referral_users->count() > 0) {
            foreach ($referral_users as $key => $referral_user) {
                $referred_by = '<br><div class="badge badge-primary">Reffered By Me</div>';
                if($affiliate_id != $referral_user->affiliate_id){
                    $referral_affiliate_name = User::find($referral_user->affiliate_id)->name;
                    $referred_by = '<br><div class="badge badge-success">Reffered By Affiliate : '. $referral_affiliate_name .'</div>';
                }
                 $data[$key][] = '<div class="bg-transparent shadow-none d-flex flex-wrap m-0"><div class="border-0 p-0"><img width="55" height="55" src="' .  Helper::getImageOrThumbnailLink($referral_user) . '" alt=""></div><div class="card-block px-2"><p class="card-text"><strong>' . $referral_user->name . '</strong><br>' . $referral_user->email . '<br>'. $referral_user->phone .'<br><img height="20" alt="'.$referral_user->country_code.'" src="' . url()->asset('images/flags/16/' . strtolower($referral_user->country_code) . '.png') . '"/>'. date('d M-Y h:i A', strtotime($referral_user->created_at)). $referred_by . '</p></div></div>';
                $user_purchase = url('affiliate/user_purchase/' . $referral_user->id);
                $data[$key][] =
                    '<div class="btn-group" role="group" aria-label="Action">
                            <a class="btn btn-primary btn-sm" target="#" href="' . $user_purchase . '"><i class="fa fa-eye" aria-hidden="true"></i>&nbsp;User Purchase</a>
                    </div>';
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

    public function user_purchase($id){

        if(!auth()->check()){
            return redirect('/');
        }
        $packages = Package::select('name','id')->get();
        view()->share([
            'title' => t('user_purchase'),
            'description' => t('user_purchase'),
            'keywords' => t('user_purchase'),
        ]);

        return view('affiliate.referrals.user_purchase', compact('id','packages'));
    }

    public function user_purchase_ajax(Request $request){
        
        $data = [];
        $id = $request->id;
        $affiliate_id = auth()->user()->id;
        $referral_affiliate_id = User::find($id)->affiliate_id;

        $payments = Payment::with(['package'])->where('user_id',$id);

        if (!empty($request->get('package'))) {
            $payments = $payments->where('package_id', $request->get('package'));
        }

        if (!empty($request->get('date'))) {
            $searchDate = $request->get('date');
            $payments = $payments->whereDate('created_at', '=', $searchDate);
        }

        $recordsFiltered = $payments->count();
        $recordsTotal = $payments->count();
        $payments = $payments->get();

        if ($payments->count() > 0) {
            foreach ($payments as $key => $payment) {
                $discountText = '';
                $commissionText = '';
                if(!empty($payment->discount_value)){
                    $discount = $payment->discount_type === 'percentage' ? $payment->discount_value . '%' : '$' . $payment->discount_value;
                    $discountText = '<br><strong>Discount : </strong>' . $discount . '<br><strong>Package price after discount : </strong>$' . $payment->amount;
                }
                if($affiliate_id != $referral_affiliate_id){
                    $commission = ($payment->amount * 5) / 100;
                    $commissionText = '<br><strong>Commission : </strong>$' . number_format($commission, 2) . ' (5%)';
                }
                $data[$key][] = '<div class="bg-transparent shadow-none flex-row flex-wrap m-0"><div class="card-block px-2"><p class="card-text">' . $payment->package->name . '<br>' . date('d M-Y h:i A', strtotime($payment->created_at)) . '</p></div></div>';
                $data[$key][] = '<div class="bg-transparent shadow-none flex-row flex-wrap m-0"><div class="card-block px-2"><p class="card-text"><strong>Package Price : </strong>$' . $payment->package->price . $discountText . $commissionText .'</p></div></div>';
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

    public function referral_affiliates()
    {
        if(!auth()->check()){
            return redirect()->back();
        }
        view()->share([
            'title' => t('referral_users'),
            'description' => t('referral_users'),
            'keywords' => t('referral_users'),
        ]);

        return view('affiliate.referral-affiliates.index');
    }

    public function referral_affiliates_ajax(Request $request)
    {
        $data = [];
        $affiliate_id = auth()->user()->id;
        $referral_affiliates = User::where(['affiliate_id'=> $affiliate_id, 'user_type_id'=> 5])->where('deleted_at', NULL)->orderBy('id', 'DESC');

        if (!empty($request->get('search_new'))) {
            $search = $request->get('search_new');
            $referral_affiliates = $referral_affiliates->where(function ($query) use ($search) {
                $query->where('name', 'LIKE', "%{$search}%")
                ->orwhere('email', 'LIKE', "%{$search}%")
                ->orwhere('phone', 'LIKE', "%{$search}%");
            });
        }

        $recordsFiltered = $referral_affiliates->count();
        $recordsTotal = $referral_affiliates->count();
        $referral_affiliates = $referral_affiliates->get();

        if ($referral_affiliates->count() > 0) {
            foreach ($referral_affiliates as $key => $referral_affiliate) {
                $data[$key][] = '<div class="bg-transparent shadow-none d-flex flex-wrap m-0"><div class="border-0 p-0"><img width="55" height="55" src="' .  Helper::getImageOrThumbnailLink($referral_affiliate) . '" alt=""></div><div class="card-block px-2"><p class="card-text"><strong>' . $referral_affiliate->name . '</strong><br>' . $referral_affiliate->email . '<br>'. $referral_affiliate->phone .'<br><img height="20" alt="'.$referral_affiliate->country_code.'" src="' . url()->asset('images/flags/16/' . strtolower($referral_affiliate->country_code) . '.png') . '"/>'. date('d M-Y h:i A', strtotime($referral_affiliate->created_at)) . '</p></div></div>';
                $affiliate_companies = url('affiliate/referral_users?affiliate_id=' . $referral_affiliate->id);
                $data[$key][] =
                    '<div class="btn-group" role="group" aria-label="Action">
                            <a class="btn btn-primary btn-sm" href="' . $affiliate_companies . '"><i class="fa fa-eye" aria-hidden="true"></i>&nbsp;View Companies</a>
                    </div>';
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
}