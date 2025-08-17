<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AffiliatesCommissionSlots extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'affiliates_commision_slots';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['affiliate_id', 'min_amount','max_amount','commission'];

    public static function storeCommissions($user_id)
    {
        $commissions = [
            ['affiliate_id' => $user_id, 'min_amount' => 0, 'max_amount' => 1000, 'commission' => 10],
            ['affiliate_id' => $user_id, 'min_amount' => 1001, 'max_amount' => 3000, 'commission' => 15],
            ['affiliate_id' => $user_id, 'min_amount' => 3001, 'max_amount' => 7000, 'commission' => 20],
            ['affiliate_id' => $user_id, 'min_amount' => 7001, 'max_amount' => 15000, 'commission' => 25],
            ['affiliate_id' => $user_id, 'min_amount' => 15001, 'max_amount' => 30000, 'commission' => 30],
            ['affiliate_id' => $user_id, 'min_amount' => 30001, 'max_amount' => 60000, 'commission' => 35],
            ['affiliate_id' => $user_id, 'min_amount' => 60001, 'max_amount' => 100000, 'commission' => 40],
            ['affiliate_id' => $user_id, 'min_amount' => 100001, 'max_amount' => 160000, 'commission' => 45],
            ['affiliate_id' => $user_id, 'min_amount' => 160001, 'max_amount' => 250000, 'commission' => 50],
        ];

        foreach ($commissions as $data) {
            self::create($data);
        }
        return true;
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'affiliate_id');
    }

    public static function get_affiliates_commission_slots($request)
    {
        $limit = $request->get('length', $request->get('limit', 30));

        $affiliates_commission_slot = self::build_query($request)
            ->with('user')
            ->paginate($limit)
            ->appends($request->query());

        return $affiliates_commission_slot;
    }

    protected static function build_query($request)
    {
        $affiliates_commission_slots = AffiliatesCommissionSlots::select('affiliates_commision_slots.*')->with('user');

        if (!empty($request->get('affiliate'))) {
            $affiliates_commission_slots = $affiliates_commission_slots->where('affiliate_id', $request->get('affiliate'));
        }
        if (!empty($request->get('affiliate_id'))) {
            $affiliates_commission_slots = $affiliates_commission_slots->where('affiliate_id', $request->get('affiliate_id'));
        }
        return $affiliates_commission_slots;
    }

    public static function get_affiliates_commission_slots_count()
    {
        $affiliates_commission_slots = AffiliatesCommissionSlots::select('affiliates_commision_slots.*')->with('user');
        $affiliates_commission_slots_count = $affiliates_commission_slots->get()->count();
        return $affiliates_commission_slots_count;
    }

        public static function get_affiliates_commission_slots_filter_count($request)
    {
        return self::build_query($request)->count();
    }

    public static function get_commission_slot($affiliate_id, $amount)
    {
        $slot = AffiliatesCommissionSlots::where('affiliate_id', $affiliate_id)
            ->where('min_amount', '<=', $amount)
            ->where('max_amount', '>=', $amount)
            ->first();

        return $slot;
    }
}
