<?php

namespace App\Http\Controllers\Affiliate;

use App\Helpers\EmailCheck;
use App\Helpers\Helper;
use App\Models\AffiliateBankDetail;
use App\Models\City;
use Illuminate\Http\Request;
use App\Helpers\Localization\Country as CountryLocalization;
use App\Helpers\Localization\Helpers\Country as CountryLocalizationHelper;

class AffiliateBankDetailsController extends AffiliateBaseController
{
    public function index()
    {
        if(!auth()->check()){
            return redirect()->back();
        }
        $user_id = auth()->id();

        $data = AffiliateBankDetail::firstWhere('user_id', $user_id); 
        if (!$data) {
            $data = new AffiliateBankDetail();
            $data->country_code = null;
            $data->city = null;
        }
        $data['cities'] = City::select('id', 'name')->where('country_code',$data->country_code)->orderBy('name')->get();
        $data['city_id'] = $data->city;
        $data['countries'] = CountryLocalizationHelper::transAll(CountryLocalization::getCountries());
        
        view()->share([
            'title' => t('bank_details'),
            'description' => t('bank_details'),
            'keywords' => t('bank_details'),
        ]);

        return view('affiliate.bank_details.index', compact('data'));
    }


    public function store(Request $request)
    {
        $user_id = auth()->id();
        $validatedData = $request->validate([
            'service_type' => 'required|in:paypal,bank',
            'email' => 'required_if:service_type,paypal|nullable|email|max:255',
            'bank_name' => 'required_if:service_type,bank|string|max:255',
            'beneficiary_name' => 'required_if:service_type,bank|string|max:255',
            'address' => 'required_if:service_type,bank|string|max:256',
            'IBAN' => 'required_if:service_type,bank|string|max:50',
            'country_code' => 'required_if:service_type,bank',
            'city' => 'required_if:service_type,bank',
            'state' => 'required_if:service_type,bank',
            'bank_address' => 'required_if:service_type,bank|string|max:256',
            'swift_code' => 'required_if:service_type,bank|string|max:50',
        ]);

        $data = [
            'service_type' => $validatedData['service_type'],
        ];

        if ($validatedData['service_type'] === 'paypal') {
            $data['email'] = $validatedData['email'] ?? null;
            if(!empty($data['email'])){
                $response = EmailCheck::verify_user_email($data['email']);
                if (!empty($response) && $response->score <= 3 && $response->status == 'undeliverable') {
                    flash(t("The email address you entered is not valid."))->error();
                    return redirect()->back();
                }
            }
        
            $data = array_merge($data, [
                'bank_name' => null,
                'beneficiary_name' => null,
                'address' => null,
                'IBAN' => null,
                'country_code' => null,
                'city' => null,
                'state' => null,
                'bank_address' => null,
                'swift_code' => null,
            ]);
        } else {
            $data = array_merge($data, [
                'email' => null,
                'bank_name' => $validatedData['bank_name'],
                'beneficiary_name' => $validatedData['beneficiary_name'],
                'address' => $validatedData['address'],
                'IBAN' => $validatedData['IBAN'],
                'country_code' => $validatedData['country_code'],
                'city' => $validatedData['city'],
                'state' => $validatedData['state'],
                'bank_address' => $validatedData['bank_address'],
                'swift_code' => $validatedData['swift_code'],
            ]);
        }
        $affiliatedata = '';
        $user_url = admin_url() . '/affiliates?search=' . auth()->user()->email;
        $name = auth()->user()->name;
        $description = "An affiliate User Name: <b> <a href='$user_url'>$name</a></b> updated his bank details at " .date('Y-m-d H:i:s');
        Helper::activity_log($description);
        $affiliateDescription = Helper::affiliateDescriptionData($affiliatedata, 'bank_details');
        if(!empty($affiliateDescription)){
            Helper::activity_log($affiliateDescription,auth()->user()->id);
        }

        AffiliateBankDetail::updateOrCreate(
            ['user_id' => $user_id],
            $data
        );

        flash(t("Your account details have been updated successfully"))->success();

        return redirect()->back();
    }

}
