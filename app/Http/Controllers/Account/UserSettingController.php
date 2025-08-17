<?php

namespace App\Http\Controllers\Account;

use App\Helpers\Twilio;
use App\Models\EmployeeSkill;
use App\Models\MetaTag;
use App\Models\User;
use App\Models\UserSetting;
use Illuminate\Http\Request;
use Carbon\Carbon;

class UserSettingController extends AccountBaseController
{
    public function index()
    {
        $data = [];
        $title = t('Settings');
        view()->share('title', $title);
        
        $data['employee_skills'] = EmployeeSkill::getAllskill();
        $data['user_setting'] = UserSetting::getUserSetting();

        return appView('account.user_settings', $data);

    }
    
    public function user_setting_ajax()
    {
        $user_id = auth()->user()->id;
        $success = false;
        $skills = [];

        if(IS_WHATSAPP_ALLOWED){
            $userSetting = UserSetting::where('user_id',$user_id)->first();
            $isFirstLoginToday = empty(auth()->user()->is_login_at) || !Carbon::parse(auth()->user()->is_login_at)->isToday();

            if ($isFirstLoginToday && (!$userSetting || (empty($userSetting->whatsapp_number) || $userSetting->is_verified == 0))) 
            {
                $success = true;
                $skills = EmployeeSkill::getAllskill();
            }

            User::find(auth()->user()->id)->update([
                'is_login_at' => now(),
            ]);
        }

        return response()->json([
            'success' => $success,
            'skills' => $skills,
        ]);
    }

    public function user_setting_update(Request $request)
    {
        $user_id = auth()->user()->id;

        $userSetting = UserSetting::firstOrNew(['user_id' => $user_id]);

        $userSetting->skills_set = implode(",", $request->input('skills_set', []));
        $userSetting->user_id = $user_id;

        $phone = trim($request->get('whatsapp_number'));
        $countryCode = '+' . config('country.phone');
        $twilio = new Twilio();

        if (!str_starts_with($phone, $countryCode)) {
            $whatsapp_number = $countryCode . $phone;
            $result = $twilio->isPhoneNumberValid($whatsapp_number);
        } else {
            $whatsapp_number = $phone;
            $result = $twilio->isPhoneNumberValid($whatsapp_number);
        }

        if ($result['valid']) {
            $userSetting->is_verified = 1;
            $userSetting->whatsapp_number = $whatsapp_number;

            $userSetting->save();

            flash(t('Your account settings has updated successfully'))->success();
        } else {
            $userSetting->is_verified = 0;
            $userSetting->save();

            flash(t('The entered WhatsApp number is invalid.'))->error();
        }

        return redirect()->back();
    }

    public function user_setting_create(Request $request)
    {
        $user_id = auth()->user()->id;
        $existingSetting = UserSetting::where('user_id', $user_id)->first();

        if ($existingSetting) {
            flash(t('Your setting already exists and cannot be created again.'))->error();
            return redirect()->back();
        }

        $phone = trim($request->get('whatsapp_number'));
        $countryCode = '+' . config('country.phone');
        $twilio = new Twilio();

        if (!str_starts_with($phone, $countryCode)) {
            $whatsapp_number = $countryCode . $phone;
        } else {
            $whatsapp_number = $phone;
        }

        $result = $twilio->isPhoneNumberValid($whatsapp_number);

        if ($result['valid']) {
            $userSetting = new UserSetting();
            $userSetting->is_verified = 1;
            $userSetting->whatsapp_number = $whatsapp_number;
            $userSetting->user_id = $user_id;
            $userSetting->skills_set = implode(",", $request->input('skills_set', []));
            $userSetting->save();
            flash(t('Your account settings have been saved successfully.'))->success();
        } else {
            flash(t('The entered WhatsApp number is invalid.'))->error();
        }
        return redirect()->back();
    }
}
