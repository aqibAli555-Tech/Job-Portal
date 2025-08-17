<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class WithdrawRequest extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'withdraw_requests';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public static function get_withdraw_requests($request)
    {
        $limit = (!empty($request->get('limit')) ? $request->get('limit') : 30);
        $limit = (!empty($request->get('length')) ? $request->get('length') : $limit);
        $withdraw_requests = self::build_query($request);
        if (!empty($request->get('start'))) {
            return $withdraw_requests->skip($request->get('start'))->take($limit)->get();
        }
        $withdraw_requests = $withdraw_requests->paginate($limit)->appends(request()->query());
        return $withdraw_requests;
    }

    public static function get_withdraw_requests_filter_count($request)
    {
        $withdraw_requests = self::build_query($request);
        $withdraw_requests_count = $withdraw_requests->get()->count();
        return $withdraw_requests_count;
    }

    protected static function build_query($request)
    {
        $withdraw_request = WithdrawRequest::select('withdraw_requests.*')->with('user','commission')->orderBy('id', 'DESC');

        if (!empty($request->get('month'))) {
            $month = $request->get('month');
            $withdraw_request = $withdraw_request->whereHas('commission', function ($query) use ($month) {
                $query->where('month', $month);
            });
        }
        
        if (!empty($request->get('year'))) {
            $year = $request->get('year');
            $withdraw_request = $withdraw_request->whereHas('commission', function ($query) use ($year) {
                $query->where('year', $year);
            });
        }

        if (!empty($request->get('filter_status'))) {
            $withdraw_request = $withdraw_request->where('status',$request->get('filter_status'));
        }

        return $withdraw_request;
    }

    public static function get_withdraw_requests_count()
    {
        $withdraw_requests = WithdrawRequest::select('withdraw_requests.*')->with('user');
        $withdraw_requests_count = $withdraw_requests->get()->count();
        return $withdraw_requests_count;
    }

    public function commission()
    {
        return $this->belongsTo(ReferralCommission::class, 'referral_commission_id');
    }

    public static function getUnseenRequests()
    {
        return self::where(function ($query) {
                $query->whereNull('last_seen');
            })->count();
    }

    public static function markAsSeen()
    {
        self::whereNull('last_seen')->update(['last_seen' => now()]);
    }
}
