<?php

namespace App\Http\Controllers;

use App\Activity;
use App\Allsaved_resume;
use App\Apphome;
use App\Applicant;
use App\Category;
use App\Causes;
use App\City;
use App\Entities;
use App\Helpers\Helper;
use App\Helpers\Tap;
use App\Http\Controllers\Auth\Traits\VerificationTrait;
use App\Models\Package;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class ApphomeController extends Controller
{
    use  VerificationTrait;


   public function subscriptionmonthly()
    {
        
        $postResponse = file_get_contents('php://input', 'R');

        Tap::create_log('', '', 'Update monthly subscription', 'post', 'Monthly tap renew request response', $postResponse, 'subscriptionmonthly', '');
        $postResponse = json_decode($postResponse);

        if (!empty($postResponse)) {
            $subscription_id = $postResponse->id;

            ///get subscription data
            $subscription_data = Tap::get_subscription($subscription_id);

            ///get vendor data
            $company_data = User::where('tap_subscription_id', $subscription_id)->first();
            if (empty($company_data)) {
                $postResponse = json_encode($postResponse);
                Tap::create_log('', '', 'Monthly Subscription update error', 'POST', 'Company data not found while renew subscription . ' . $subscription_id, $postResponse, 'subscriptionmonthly', '400');
                return false;
            }
            $captured = false;
            if (!empty($subscription_data->subscription->charges) && count($subscription_data->subscription->charges) > 0) {

                foreach ($subscription_data->subscription->charges as $charges) {
                    $charge_date = $charges->date;
                    if ($subscription_data->subscription->term->interval == 'MONTHLY') {
                        $charge_date = date('Y-m', strtotime($charge_date));
                        $todaydate = date('Y-m');

                    } else {
                        $charge_date = date('Y-m-d', strtotime($charge_date));
                        $todaydate = date('Y-m-d');
                    }

                    if ($charge_date == $todaydate) {
                        if ($charges->status == 'CAPTURED') {
                            $captured = true;
                            Helper::update_post_and_contactcard_counter($company_data->id, true, true);
                            $packge_data = Package::where('id', $company_data->package_id)->first();
                            $this->sendsubscriptionemail($packge_data, $company_data, 'Subscription Renew Successfully');
                            $postResponse = json_encode($postResponse);
                            Tap::create_log($company_data->id, '', 'Monthly Subscription update success', 'POST', 'Success Monthly subscription successfully renew for this user' . $company_data->name, $postResponse, 'subscriptionmonthly', '200');
                            break;
                        }
                    }
                }
            }

            if (!$captured) {
                Helper::update_post_and_contactcard_counter($company_data->id, true, false);
                $packge_data = Package::where('id', $company_data->package_id)->first();
                $postResponse = json_encode($postResponse);
                Tap::create_log($company_data->id, '', 'Monthly Subscription update error', 'POST', 'Error! Monthly subscription is Not renew for this user' . $company_data->name, $postResponse, 'subscriptionmonthly', '400');
            }
        }
    }

    public function sendsubscriptionemail($packeg_data, $company_data, $subject)
    {
        $data['email'] = $company_data->email;
        $data['subject'] = $subject;
        $data['from'] = getenv('MAIL_USERNAME');
        $data['myName'] = $company_data->name;
        $data['package_name'] = $packeg_data->short_name;
        $data['price'] = $packeg_data->price . ' ' . $packeg_data->currency_code;
        $data['header'] = 'Renew Package Subscription';
        $data['view'] = 'emails/renew_subscription_email';
        $helper = new Helper();
        $response = $helper->send_email($data);
    }
}