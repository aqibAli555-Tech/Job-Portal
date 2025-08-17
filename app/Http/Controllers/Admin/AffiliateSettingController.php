<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\AffiliateSetting;
use Illuminate\Http\Request;

class AffiliateSettingController extends AdminBaseController
{
    public function index(Request $request)
    {
        $title = trans('admin.affiliate_settings');
        $breadcumbs = [
            [
                'title' => 'Dashboard',
                'link' => admin_url('dashboard')
            ],
            [
                'title' => 'Affiliate Settings',
                'link' => 'javascript:void(0)'
            ]
        ];
        $affiliate_settings = AffiliateSetting::first();

        return view('admin.affiliates.settings.index', compact('title', 'breadcumbs','affiliate_settings'));
    }

    public function store(Request $request){
        $affiliate_settings = AffiliateSetting::first();
        if ($affiliate_settings) {
            $affiliate_settings->update([
                'package_discount_type' => $request->input('package_discount_type'),
                'package_discount_value' => $request->input('package_discount_value'),
                'affiliate_to_affiliate_commission_value' => $request->input('affiliate_to_affiliate_commission_value'),
                'affiliate_to_affiliate_commission_type' => $request->input('affiliate_to_affiliate_commission_type')
            ]);    
            $type = 'updated';
            flash('Affiliate settings updated successfully')->success();
        } else {
            $data = [
                'package_discount_type' => $request->input('package_discount_type'),
                'package_discount_value' => $request->input('package_discount_value'),
                'affiliate_to_affiliate_commission_value' => $request->input('affiliate_to_affiliate_commission_value'),
                'affiliate_to_affiliate_commission_type' => $request->input('affiliate_to_affiliate_commission_type')
            ];
            $type = 'added';
            AffiliateSetting::create($data);
    
            flash('Affiliate settings added successfully')->success();
        }
        $affiliateDescription = Helper::adminAffiliateActivityLogDescription($type, 'affiliate_settings');
        if(!empty($affiliateDescription)){
            Helper::activity_log($affiliateDescription);
        }
    
        return redirect()->back();
    }
}
