<?php

namespace App\Models;

use App\Helpers\Helper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReferralCommission extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'referral_commissions';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public function affiliateUser()
    {
        return $this->belongsTo(User::class, 'affiliate_id');
    }


    public static function get_referral_commissions($request)
    {
        $limit = (!empty($request->get('limit')) ? $request->get('limit') : 30);
        $limit = (!empty($request->get('length')) ? $request->get('length') : $limit);
        $referral_commission = self::build_query($request);
        $referral_commission = $referral_commission->with(['affiliateUser']);
        if (!empty($request->get('start'))) {
            return $referral_commission->skip($request->get('start'))->take($limit)->get();
        }
        $referral_commission = $referral_commission->paginate($limit)->appends(request()->query());
        return $referral_commission;
    }

    public static function get_referral_commission_filter_count($request)
    {
        $referral_commission = self::build_query($request);
        $referral_commission_count = $referral_commission->get()->count();
        return $referral_commission_count;
    }

    protected static function build_query($request)
    {
        $referral_commission = ReferralCommission::select('referral_commissions.*')->with('affiliateUser')->orderBy('id', 'DESC');

        if (!empty($request->get('referrer'))) {
            $referral_commission = $referral_commission->where('affiliate_id', $request->get('referrer'));
        }
        
        if (!empty($request->get('month'))) {
            $month = $request->get('month');
            $referral_commission = $referral_commission->where(function ($query) use ($month) {
                $query->where('month', 'LIKE', "%{$month}%");
            });
        }
        
        if (!empty($request->get('year'))) {
            $year = $request->get('year');
            $referral_commission = $referral_commission->where(function ($query) use ($year) {
                $query->where('year', 'LIKE', "%{$year}%");
            });
        }

        if (!empty($request->get('status'))) {
            $referral_commission = $referral_commission->where('status',$request->get('status'));
        }

        return $referral_commission;
    }

    public static function get_referral_commission_count()
    {
        $referral_commission = ReferralCommission::select('referral_commissions.*')->with('affiliateUser');
        $referral_commission_count = $referral_commission->get()->count();
        return $referral_commission_count;
    }

    public static function total_affiliation_revenue()
    {
        $paidRevenue = ReferralCommission::where('status', 'paid')->sum('total_revenue');
        $currentMonthRevenue = self::current_month_affiliation_revenue();
        return round($paidRevenue + $currentMonthRevenue,2);
    }

    public static function total_affiliation_commission()
    {
        $paidCommission = ReferralCommission::where('status', 'paid')->sum('total_commission');
        $currentMonthCommission = self::current_month_affiliation_commission();
        return round($paidCommission + $currentMonthCommission, 2);
    }

    public static function last_month_affiliation_revenue()
    {
        $last_month_affiliation_revenue = ReferralCommission::whereYear('created_at', now()->subMonth()->year)
        ->whereMonth('created_at', now()->subMonth()->month)
        ->where('status', 'paid')->sum('total_revenue');
        return number_format($last_month_affiliation_revenue, 2);
    }

    public static function last_month_affiliation_commission()
    {
        $last_month_affiliation_commission = ReferralCommission::whereYear('created_at', now()->subMonth()->year)
        ->whereMonth('created_at', now()->subMonth()->month)
        ->where('status', 'paid')->sum('total_commission');
        return number_format($last_month_affiliation_commission, 2);
    }

    public static function current_month_affiliation_revenue()
    {
        $currentMonthReferral = Helper::get_current_month_referral_commission();
        $totalRevenue = 0;

        foreach ($currentMonthReferral as $item) {
            $totalRevenue += $item['my_revenue'];
        }

        return $totalRevenue;
    }

    public static function current_month_affiliation_commission()
    {
        $currentMonthReferral = Helper::get_current_month_referral_commission();
        $totalCommission = 0;

        foreach ($currentMonthReferral as $item) {
            $totalCommission += $item['my_commission'];
        }

        return $totalCommission;
    }
}