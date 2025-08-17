<?php

namespace App\Http\Controllers\Affiliate;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\AffiliatesCommissionSlots;
use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\ReferralCommission;
use App\Models\User;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;


class DashboardController extends AffiliateBaseController
{
    public $data = [];
    public function __construct()
    {
        parent::__construct();
    }

    public function dashboard(Request $request)
    {
        $affiliate_id = auth()->id();
        $filter = $request->input('filter', 'overall');

        $this->data = Cache::remember(time(), 3000000, function () use ($affiliate_id, $filter) {
            $data['title'] = t('Dashboard');

            $referral_users = User::where('affiliate_id', $affiliate_id)->where('is_active', 1)->pluck('id');

            $amount = Payment::whereIn('user_id', $referral_users)
                ->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
                ->sum('amount');

            $slot = AffiliatesCommissionSlots::get_commission_slot($affiliate_id, $amount);

            $data['commission_slots'] = AffiliatesCommissionSlots::where('affiliate_id', $affiliate_id)->get();
            $data['slot'] = $slot;

            $data += Helper::get_affiliate_dashboard_metrics($affiliate_id);
            
            return $data;
        });

        if ($request->ajax()) {
            return view('affiliate.dashboard.inc.stats-boxes', ['data' => $this->data]);
        }

        return view('affiliate.dashboard.index', [
            'title' => $this->data['title'],
            'breadcrumbs' => [['title' => 'Dashboard', 'link' => 'javascript:void(0)']],
            'data' => $this->data,
        ]);
    }

}