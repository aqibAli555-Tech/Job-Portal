<?php

namespace App\Helpers;

use App\Models\Availability;
use App\Models\Nationality;
use App\Models\City;
use App\Models\Country;


class EmailHelper
{
    public static function senduseremail($user)
    {
        $email_data['verificationUrl'] = url('verify/user/email/' . $user->email_token);
        $email_data['email'] = $user->email;
        $email_data['subject'] = 'Email Verification';
        $email_data['myName'] = $user->name;
        if ($user->user_type_id == 1) {
            $email_data['role'] = 'Employer';
        } else {
            $email_data['role'] = 'Employee';
        }
        $email_data['header'] = 'Welcome to Hungry For Jobs!';
        $email_data['view'] = 'emails.email_verification';
        $helper = new Helper();
        $helper->send_email($email_data);
    }

     public static function sendadminemail($admin, $user)
    {
        if ($user->user_type_id == 1) {
            $data['role'] = 'Employer';
        } else {
            $data['role'] = 'Employee(Job Seeker)';
        }
        if (!empty($user->availability)) {
            $availability = Availability::where('id', $user->availability)->first();
            if (!empty($availability)) {
                $data['availability'] = $availability->name;
            }
        }
        if (!empty($user->nationality)) {
            $nationality = Nationality::where('id', $user->nationality)->first();
            if (!empty($nationality)) {
                $data['nationality'] = $nationality->name;
            }
        }
        $country = Country::where('code', $user->country_code)->first();
        $city = City::where('id', $user->city)->first();

        $data['country_name'] = (!empty($country->name) ? $country->name : '');
        $data['city_name'] = (!empty($city->name) ? $city->name : '');
        $data['email'] = $admin->email;
        $data['subject'] = 'New ' . $data['role'] . ' Registration';
        $data['username'] = $user->name;
        $data['useremail'] = $user->email;
        $data['created_at'] = $user->created_at;
        $data['phone'] = (!empty($user->phone) ? $user->phone : '');
        $data['experience'] = (!empty($user->experiences) ? $user->experiences : '');
        $data['skill_set'] = (!empty($user->skill_set) ? $user->skill_set : '');
        $data['view'] = 'emails.admin_email_for_new_user';
        $data['header'] = 'New User';
        $helper = new Helper();
        $helper->send_email($data);

    }

    public static function senduserregisteremail($user)
    {
        $data['email'] = $user->email;
        $data['subject'] = 'We’re excited you joined Hungry For Jobs!';
        $data['view'] = 'emails.send_new_register_user_email';
        $data['header'] = 'We’re excited you joined Hungry For Jobs!';
        $helper = new Helper();
        $helper->send_email($data);
    }

    public static function sendEmployerContactedemail($user_data, $post_text, $cc = null)
    {
        $data['email'] = $user_data->email;
        $data['subject'] = 'New Applicants on your Job Posts today!';
        $data['myName'] = $user_data->name;
        $data['post_text'] = $post_text;
        $data['view'] = 'emails.send_employer_email_for_applyjob';
        $data['header'] = 'New Applicants on your Job Posts today!';
        $data['cc'] = $cc;
        $helper = new Helper();
        return $helper->send_email($data);
    }

    public static function sendadminemailToAffiliate($admin, $user)
    {
        $data['role'] = 'Affiliate';
        $country = Country::where('code', $user->country_code)->first();
        $city = City::where('id', $user->city)->first();

        $data['country_name'] = (!empty($country->name) ? $country->name : '');
        $data['city_name'] = (!empty($city->name) ? $city->name : '');
        $data['name'] = $admin->name;
        $data['email'] = $admin->email;
        $data['subject'] = 'New ' . $data['role'] . ' Registration - '. $user->name;
        $data['username'] = $user->name;
        $data['useremail'] = $user->email;
        $data['created_at'] = $user->created_at;
        $data['phone'] = (!empty($user->phone) ? $user->phone : '');
        $data['view'] = 'emails.admin_email_for_new_affiliate';
        $data['header'] = 'New ' . $data['role'] . ' Registration - '. $user->name;
        $helper = new Helper();
        $helper->send_email($data);

    }

    public static function sendaffiliateregisteremail($affiliate)
    {
        $data['email'] = $affiliate->email;
        $data['name'] = $affiliate->name;
        $data['subject'] = 'Welcome to Hungry For Jobs – Let’s Start Earning Together! 🍽️';
        $data['view'] = 'emails.send_new_affiliate_register_email';
        $data['header'] = 'Welcome to Hungry For Jobs – Let’s Start Earning Together! 🍽️';
        $helper = new Helper();
        $helper->send_email($data);
    }

    public static function notifyReferrerOfNewAffiliateRegistration($affiliate)
    {
        $data['email'] = $affiliate['referrer_affiliate']->email;
        $data['name'] = $affiliate['referrer_affiliate']->name;
        $data['new_affiliate_name'] = $affiliate['affiliate']->name;
        $data['subject'] = '🎉 You Just Referred a New Affiliate – And Earned Yourself 5% for Life!';
        $data['header'] = '🎉 You Just Referred a New Affiliate – And Earned Yourself 5% for Life!';
        $data['view'] = 'emails.referrer_affiliate_email_register_new_affiliate';
        $helper = new Helper();
        $helper->send_email($data);
    }

    public static function sendWithdrawRequestEmail($emailData){
        $data['affiliate_data'] = $emailData['email_data'];
        $data['email'] = $emailData['user']->email;
        $data['name'] = $emailData['user']->name;
        $data['subject'] = 'Confirmation of Your Withdrawal Request';
        $data['header'] = 'Confirmation of Your Withdrawal Request';
        $data['view'] = 'emails.withdraw_request_email';
        $helper = new Helper();
        $helper->send_email($data);
    }

    public static function sendEmailToReffererForPackageBuy($emailData){
        $data['company_name'] = $emailData['company_name'];
        $data['email'] = $emailData['referral_by']->email;
        $data['name'] = $emailData['referral_by']->name;
        $data['package'] = $emailData['package'];
        $data['package_discount'] = $emailData['package_discount'];
        $data['subject'] = '🎉 You Did It! ' . $emailData['company_name'] . ' Just Subscribed Thanks to You 🙌';
        $data['header'] = '🎉 You Did It! ' . $emailData['company_name'] . ' Just Subscribed Thanks to You 🙌';
        $data['view'] = 'emails.reffer_email_for_affiliate_company_buy_package';
        $helper = new Helper();
        $helper->send_email($data);
    }

    public static function sendEmailToReffererAffiliateForPackageBuy($emailData){
        $data['company_name'] = $emailData['company_name'];
        $data['email'] = $emailData['referral_by']->email;
        $data['name'] = $emailData['referral_by']->name;
        $data['referral_affiliate_name'] = $emailData['referral_affiliate_name'];
        $data['subject'] = '💸 You’ve Just Earned 5% – For Life! 🙌';
        $data['header'] = '💸 You’ve Just Earned 5% – For Life! 🙌';
        $data['view'] = 'emails.refferer_affiliate_company_buy_package';
        $helper = new Helper();
        $helper->send_email($data);
    }

    public static function sendEmailToAffiliateForCommissionCalculate($emailData){
        $data['email'] = $emailData['email'];
        $data['name'] = $emailData['name'];
        $data['month'] = $emailData['month'];
        $data['year'] = $emailData['year'];
        $data['withdraw_date'] = $emailData['withdraw_date'];
        $data['subject'] = '💼 Your Commission for ' . $emailData['month'] . ' ' . $emailData['year'] . ' Is Ready! 🎉';
        $data['header'] = '💼 Your Commission for ' . $emailData['month'] . ' ' . $emailData['year'] . ' Is Ready! 🎉';
        $data['view'] = 'emails.email_to_affiliate_commission_calculate';
        $helper = new Helper();
        $helper->send_email($data);
    }

    public static function sendEmailToAffiliateForInformWithdrawCommission($emailData){
        $data['email'] = $emailData['email'];
        $data['name'] = $emailData['name'];
        $data['month'] = $emailData['month'];
        $data['year'] = $emailData['year'];
        $data['subject'] = '💸 Your Commission is Ready for Withdrawal!';
        $data['header'] = '💸 Your Commission is Ready for Withdrawal!';
        $data['view'] = 'emails.email_to_affiliate_commission_withdraw';
        $helper = new Helper();
        $helper->send_email($data);
    }
}