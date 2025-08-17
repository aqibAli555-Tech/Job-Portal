<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AffiliatesCommissionSlots;
use App\Models\User;
use Illuminate\Http\Request;
use App\Helpers\Helper;


class AffiliatesCommissionSlotController extends Controller
{

    public function get_affiliated_commission_slot($id)
    {
        $title = 'Affiliate Commission Slot';
        $user = User::find($id);

        if (!$user) {
            flash(trans('Affiliate not found.'))->error();
            return redirect()->back();
        }

        $affiliate_name = $user->name;
        $affiliates_commission_slots = AffiliatesCommissionSlots::where('affiliate_id', $id)->get();

        $breadcumbs = [
            [
                'title' => 'Dashboard',
                'link' => admin_url('dashboard')
            ],
            [
                'title' => 'Affiliates Commission Slot',
                'link' => 'javascript:void(0)'
            ]
        ];

        return view('admin.affiliates-commission-slot.list', compact(
            'title', 'breadcumbs', 'id', 'affiliates_commission_slots', 'affiliate_name'
        ));
    }


    public function delete_affiliated_commission_slot($id)
    {
        $affiliate_commission_slot = AffiliatesCommissionSlots::find($id);
        
        if (empty($affiliate_commission_slot)) {
            return response()->json(['success' => false, 'message' => 'Requested slot not found. '], 404);
        }
        $user = User::where('id', $affiliate_commission_slot->affiliate_id)->select('email','name')->first();
        $affiliateDescription = Helper::adminAffiliateActivityLogDescription($user, 'delete_slot');
        if(!empty($affiliateDescription)){
            Helper::activity_log($affiliateDescription);
        }
        $affiliate_commission_slot->delete();
        return response()->json(['success' => true, 'message' => 'Slot deleted successfully.']);
    }

    public function edit_affiliated_commission_slot($id)
    {
        $slot_detail = AffiliatesCommissionSlots::find($id);

        if (!$slot_detail) {
            return response()->json(['success' => false, 'message' => 'Requested slot not found. '], 404);
        }
        return response()->json(['success' => true, 'data' => $slot_detail]); 
    }

    public function update_affiliated_commission_slot(Request $request)
    {
        $id = $request->slot_id;
        $user = User::where('id', $request->affiliate_id)->select('email','name')->first();

        if(empty($id)){

            $exists = AffiliatesCommissionSlots::where('affiliate_id', $request->affiliate_id)
                        ->where(function ($query) use ($request) {
                            $query->whereBetween('min_amount', [$request->min_amount, $request->max_amount])
                                ->orWhereBetween('max_amount', [$request->min_amount, $request->max_amount])
                                ->orWhere(function ($q) use ($request) {
                                    $q->where('min_amount', '<=', $request->min_amount)
                                        ->where('max_amount', '>=', $request->max_amount);
                                });
                        })->exists();

            if ($exists) {

                flash('A commission slot already exists for the specified range. Please choose a different range or update the existing slot.')->error();

                $redirect_url = admin_url('affiliated-commission-slot').'/'. $request->input('affiliate_id');
                return redirect($redirect_url);
            }

            $data = [
                'affiliate_id' => $request->input('affiliate_id'),
                'min_amount' => $request->input('min_amount'),
                'max_amount' => $request->input('max_amount'),
                'commission' => $request->input('commission'),
            ];
            $affiliateDescription = Helper::adminAffiliateActivityLogDescription($user, 'add_slot');
            if(!empty($affiliateDescription)){
                Helper::activity_log($affiliateDescription);
            }

            AffiliatesCommissionSlots::create($data);
            flash('Slot added successfully')->success();

        }else{
            $slotDetail = AffiliatesCommissionSlots::find($id);

            if (!$slotDetail) {

                flash('Slot not found.')->error();
                return redirect()->back();
            }

            $exists = AffiliatesCommissionSlots::where('affiliate_id', $slotDetail->affiliate_id)
                        ->where('id', '!=', $slotDetail->id)
                        ->where(function ($query) use ($request) {
                            $query->whereBetween('min_amount', [$request->min_amount, $request->max_amount])
                                ->orWhereBetween('max_amount', [$request->min_amount, $request->max_amount])
                                ->orWhere(function ($q) use ($request) {
                                    $q->where('min_amount', '<=', $request->min_amount)
                                        ->where('max_amount', '>=', $request->max_amount);
                                });
                        })->exists();

            if ($exists) {

                flash('A commission slot already exists for the specified range. Please choose a different range or update the existing slot.')->error();
                $redirect_url = admin_url('affiliated-commission-slot').'/'. $slotDetail->affiliate_id;
                return redirect($redirect_url);
            }
            $affiliateDescription = Helper::adminAffiliateActivityLogDescription($user, 'edit_slot');
            if(!empty($affiliateDescription)){
                Helper::activity_log($affiliateDescription);
            }
            $slotDetail->update([
                'min_amount' => $request->min_amount,
                'max_amount' => $request->max_amount,
                'commission' => $request->commission,
            ]);

            flash(trans('Slot updated successfully'))->success();
        }
        
        $redirect_url = admin_url('affiliated-commission-slot').'/'. $request->input('affiliate_id');
        return redirect($redirect_url);
    }
}
