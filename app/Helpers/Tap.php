<?php

namespace App\Helpers;

use App\Activity;
use App\Models\Package;
use App\Models\PaymentSetting;
use App\Models\TapLog;
use App\Models\User;
use App\Models\AffiliateSetting;

class Tap
{
    public $token;
    public $return_url;
    public $error_ret_url;
    public $basURL;
    public $merchantCode;
    public $is_live = 0;

    public static function add_subscription($package_id, $old_tap_data)
    {
        $user_data = User::where('id', auth()->user()->id)->first();
        $package_data = Package::where('id', $package_id)->first();
        $paymnet_data = \App\Models\Payment::where('user_id', auth()->user()->id)->orderBy('id', 'DESC')->first();
        
        $affiliate_setting = AffiliateSetting::first();
        $discount_type = null;
        $discount_value = null;
        $price = $package_data->price;
        if($user_data->affiliate_id != 0 && $affiliate_setting){
            $discount_value = $affiliate_setting->package_discount_value;
            $discount_type = $affiliate_setting->package_discount_type;

            if($discount_type === 'fixed'){
                $price = $price - $discount_value;
            }else{
                $price = $price - ($price * $discount_value / 100);
            }
        }
        
        $old_tap_data = self::payemntDetails($paymnet_data->transaction_id);
        $subscrption_date = date("Y-m-d", strtotime(date('Y-m-d') . " +1 day"));
        $curl = curl_init();
        $bodyArray = array(
            'term' => array(
                'interval' => 'MONTHLY',
                'period' => 60,
                'from' => date('Y-m-d') . 'T13:59:00',
                'due' => 0,
                'auto_renew' => true,
                'timezone' => 'Asia/Kuwait',
            ),
            'trial' => array(
                'days' => 0,
                'amount' => 0,
            ),
            'charge' => array(
                'amount' => $price,
                'currency' => $package_data->currency_code,
                'description' => $package_data->name.' '.$package_data->description,
                'statement_descriptor' => '',
                'metadata' => array(
                    'udf1' => '',
                    "udf2" => ""
                ),
                'reference' => array(
                    'transaction' => $paymnet_data->transaction_id,
                    "order" => $paymnet_data->id,
                ),
                'receipt' => array(
                    'email' => true,
                    "sms" => true,
                ),
                'customer' => array(
                    'id' => $old_tap_data->customer->id,
                ),
                'source' => array(
                    'id' => $old_tap_data->card->id,
                ),
                'post' => array(
                    "url" => lurl('tap/update-subscription'),
                ),
            ),
        );
        $url = "https://api.tap.company/v2/subscription/v1/";
        $body = json_encode($bodyArray);

        $tap_log_id = self::create_log(auth()->user()->id, $url, 'Add subscription', 'POST', $body, 'Sending subscription request', 'add_subscription', '');
        $payment_data = PaymentSetting::where('id', 1)->where('Tap_enabled', 1)->first();
        if (!empty($payment_data)) {
            $secret_key = $payment_data->secret_key;

            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => $body,
                CURLOPT_HTTPHEADER => array(
                    "authorization: Bearer $secret_key",
                    "content-type: application/json"
                ),
            ));
            $response = curl_exec($curl);
            $err = curl_error($curl);
            $header_response_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            curl_close($curl);
            self::create_log(auth()->user()->id, $url, 'Add subscription', 'POST', $body, $response, 'add_subscription', $header_response_code, $tap_log_id);
            $response = json_decode($response);

            if ($response->status = 'ACTIVE' && !empty($response->id)) {
                $Useruodate = array(
                    'tap_subscription_id' => $response->id,
                );
                User::where('id', auth()->user()->id)->update($Useruodate);
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public static function payemntDetails($tap_id)
    {
        $url = "https://api.tap.company/v2/charges/" . $tap_id;
        $payment_data = PaymentSetting::where('id', 1)->where('Tap_enabled', 1)->first();;
        if (!empty($payment_data)) {
            $secret_key = $payment_data->secret_key;

            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_POSTFIELDS => "{}",
                CURLOPT_HTTPHEADER => array(
                    "authorization: Bearer $secret_key",
                ),
            ));
            $response = curl_exec($curl);
            $err = curl_error($curl);
            $header_response_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            self::create_log(auth()->user()->id, $url, 'Payment details', 'GET', 'Getting payment details for ' . $tap_id, $response, 'payemntDetails', $header_response_code);
            $response = json_decode($response);
            curl_close($curl);
            return $response;
        } else {
            return false;
        }
    }

    public static function create_log($user_id, $url, $title, $method, $request, $response, $function_name, $header_response_code, $update_id = null)
    {
        $values = array(
            'user_id' => $user_id,
            'url' => $url,
            'title' => $title,
            'method' => $method,
            'request' => $request,
            'response' => $response,
            'function_name' => $function_name,
            'header_response_code' => $header_response_code
        );

        if (empty($update_id)) {
            $tap = TapLog::create($values);
            return $tap->id;
        } else {
            $tap = TapLog::where('id', $update_id)->update($values);
        }
    }

    public static function update_subscription($package_id, $user_id)
    {
        $user_data = User::where('id', $user_id)->first();
        $package_data = Package::where('id', $package_id)->first();
        
        $affiliate_setting = AffiliateSetting::first();
        $discount_type = null;
        $discount_value = null;
        $price = $package_data->price;
        if($user_data->affiliate_id != 0 && $affiliate_setting){
            $discount_value = $affiliate_setting->package_discount_value;
            $discount_type = $affiliate_setting->package_discount_type;

            if($discount_type === 'fixed'){
                $price = $price - $discount_value;
            }else{
                $price = $price - ($price * $discount_value / 100);
            }
        }
        
        
        $curl = curl_init();
        $bodyArray = array(
            'subscription_id' => $user_data->tap_subscription_id,
            'amount' => $price,
            'auto-renew' => true,
            'description' => 'Subscription Updated for Hfj Company' . $user_data->name,
            'statement_descriptor' => true,

            'metadata' => array(
                'udf1' => 0,
                'udf2' => 0,
            ),
            'receipt' => array(
                'email' => true,
                'sms' => true,
            ),
        );

        $url = "https://api.tap.company/v2/subscription/v1/";
        $body = json_encode($bodyArray);
        $tap_log_id = self::create_log(auth()->user()->id, $url, 'Update subscription', 'POST', $body, 'Sending update subscription request', 'update_subscription', '');
        $payment_data = PaymentSetting::where('id', 1)->where('Tap_enabled', 1)->first();;
        if (!empty($payment_data)) {
            $secret_key = $payment_data->secret_key;

            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "PUT",
                CURLOPT_POSTFIELDS => $body,
                CURLOPT_HTTPHEADER => array(
                    "authorization: Bearer $secret_key",
                    "content-type: application/json"
                ),
            ));

            $response = curl_exec($curl);
            self::create_log(auth()->user()->id, $url, 'Update subscription', 'POST', $body, $response, 'update_subscription', '', $tap_log_id);
            $err = curl_error($curl);
            $header_response_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            curl_close($curl);
            $response = json_decode($response);

            if ($response->id) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public static function save_card($old_tap_data)
    {
        $payment_data = PaymentSetting::where('id', 1)->where('Tap_enabled', 1)->first();;
        if (!empty($payment_data)) {
            $secret_key = $payment_data->secret_key;
            $customer_id = $old_tap_data->customer->id;
            $source_token = $old_tap_data->source->id;
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://api.tap.company/v2/card/$customer_id",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => "{\"source\":\"$source_token\"}",
                CURLOPT_HTTPHEADER => array(
                    "authorization: Bearer $secret_key",
                    "content-type: application/json"
                ),
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);
            if ($err) {
                echo "cURL Error #:" . $err;
            } else {
                echo $response;
            }

            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://api.tap.company/v2/card/$customer_id",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => '{"source": "' . $old_tap_data->source->id . '"}',
                CURLOPT_HTTPHEADER => array(
                    "authorization: Bearer $secret_key",
                    "content-type: application/json"
                ),
            ));
            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);
//      self::create_log(auth()->user()->id, $url, 'POST', $body, $response, 'add_subscription', $header_response_code);
            $response = json_decode($response);
            return $response;
        } else {
            return false;
        }
    }

     public static function create_charge($data)
    {
        $packenaame = $data['package']['short_name'];
        $packeg_description =htmlspecialchars_decode($data['package']['description']);
        $packeg_description = strip_tags($packeg_description);

        if($data['type']=='yearly'){
            $price=$data['package']['yearly_price'];
            $extra_description='Annual Subscription Package';
            $desc = "$packenaame($extra_description) : $packeg_description";
        }else{
            $price=$data['package']['price'];
            $desc = "$packenaame : $packeg_description";
        }
        
        $affiliate_setting = AffiliateSetting::first();
        $discount_type = null;
        $discount_value = null;
        if($data['user']['affiliate_id'] != 0 && $affiliate_setting){
            $discount_value = $affiliate_setting->package_discount_value;
            $discount_type = $affiliate_setting->package_discount_type;

            if($discount_type === 'fixed'){
                $price = $price - $discount_value;
            }else{
                $price = $price - ($price * $discount_value / 100);
            }
        }

        $curl = curl_init();
        $bodyArray = array(
            'amount' => $price,
            "currency" => $data['package']['currency_code'],
            "threeDSecure" => $data['threeDSecure'],
            "save_card" => $data['save_card'],
            "customer_initiated" => $data['customer_initiated'],
            "description" => $desc,
            "statement_descriptor" => "HFJ",
            "metadata" => array(),
            "reference" => array(
                "transaction" => "",
                "order" => $data['package']['id'],
            ),
            "receipt" => array(
                "email" => true,
                "sms" => true,
            ),
            "customer" => array(
                "first_name" => $data['user']['name'],
                "middle_name" => "",
                "last_name" => "",
                "email" => $data['user']['email'],
                "phone" => array(
                    "country_code" => config('country.phone'),
                    "number" => $data['user']['phone'],
                ),
            ),
            "merchant" => array(
                "id" => "",
            ),

            "source" => array(
                "id" => "src_card",
//                "payment_method"=>"VISA,MASTERCARD"
            ),
            "post" => array(
                "url" => $data['redirect'],
            ),
            "redirect" => array(
                "url" => $data['redirect'],
            ),
        );
       

        $body = json_encode($bodyArray);
        $url = 'https://api.tap.company/v2/charges';

        $tap_log_id = self::create_log(auth()->user()->id, $url, 'Create Charge', 'POST', $body, 'Sending charge request', 'create_charge', '');
        $payment_data = PaymentSetting::where('id', 1)->where('Tap_enabled', 1)->first();
        if (!empty($payment_data)) {
            $secret_key = $payment_data->secret_key;
            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
//            CURLOPT_HEADER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => $body,
                CURLOPT_HTTPHEADER => array(
                    "authorization: Bearer $secret_key",
                    "content-type: application/json",
                ),
            ));
            $response = curl_exec($curl);
            $err = curl_error($curl);
            $header_response_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            curl_close($curl);
            self::create_log(auth()->user()->id, $url, 'Create Charge', 'POST', $body, $response, 'create_charge', $header_response_code, $tap_log_id);
            $response = json_decode($response);
            return $response;
        } else {
            return false;
        }
    }

    public static function create_charge_subscription($data)
    {
        $packenaame = $data['package']['short_name'];
        $packeg_description =htmlspecialchars_decode($data['package']['description']);
        $packeg_description = strip_tags($packeg_description);
        if($data['type']=='yearly'){
            $price=$data['package']['yearly_price'];
            $extra_description='Annual Subscription Package';
            $desc = "$packenaame($extra_description) : $packeg_description";
        }else{
            $price=$data['package']['price'];
            $desc = "$packenaame : $packeg_description";
        }
        $curl = curl_init();
        $bodyArray = array(
            'amount' => $price,
            "currency" => $data['package']['currency_code'],
            "threeDSecure" => $data['threeDSecure'],
            "save_card" => $data['save_card'],
            "customer_initiated" => $data['customer_initiated'],
            "description" => $desc,
            "statement_descriptor" => "HFJ",
            "metadata" => array(),
            "reference" => array(
                "transaction" => "",
                "order" => $data['package']['id'],
            ),
            "receipt" => array(
                "email" => true,
                "sms" => true,
            ),
            "customer" => array(
                "id" => $data['user']['tap_customer_id'],
            ),
            "merchant" => array(
                "id" => "",
            ),
            "payment_agreement" => array(
                'id' => $data['user']['tap_agreement_id'],
            ),
            'source' => array(
                'id' => $data['token_id'],
            ),
            "post" => array(
                "url" => $data['redirect'],
            ),
            "redirect" => array(
                "url" => $data['redirect'],
            ),
        );

        $body = json_encode($bodyArray);

        $url = 'https://api.tap.company/v2/charges';

        $tap_log_id = self::create_log($data['user_id'], $url, 'Create Charge', 'POST', $body, 'Sending charge request', 'create_charge', '');
        $payment_data = PaymentSetting::where('id', 1)->where('Tap_enabled', 1)->first();
        if (!empty($payment_data)) {
            $secret_key = $payment_data->secret_key;
            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
//            CURLOPT_HEADER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => $body,
                CURLOPT_HTTPHEADER => array(
                    "authorization: Bearer $secret_key",
                    "content-type: application/json",
                ),
            ));
            $response = curl_exec($curl);
            $err = curl_error($curl);
            $header_response_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            curl_close($curl);
            self::create_log($data['user_id'], $url, 'Create Charge', 'POST', $body, $response, 'create_charge', $header_response_code, $tap_log_id);
            $response = json_decode($response);
            return $response;
        } else {
            return false;
        }
    }

    public static function create_token($data)
    {
        $endpoint = 'https://api.tap.company/v2/tokens';
        $payment_data = PaymentSetting::where('id', 1)->where('Tap_enabled', 1)->first();
        if (!empty($payment_data)) {
            $secret_key = $payment_data->secret_key;

            $payload = array(
                'saved_card' => array(
                    'card_id' => $data['user']['save_card_id'],
                    'customer_id' => $data['user']['tap_customer_id'],
                ),

            );

            $payloadJson = json_encode($payload);

            $headers = array(
                'Authorization: Bearer ' . $secret_key,
                'Accept: application/json',
                'Content-Type: application/json',
            );


            $ch = curl_init($endpoint);

            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payloadJson);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            // Print the full request

            $response = curl_exec($ch);
            curl_close($ch);

            $response = json_decode($response);
            
            $header_response_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            self::create_log($data['user']['id'], $endpoint, 'Create Token', 'POST', $payloadJson, json_encode($response), 'create_token',$header_response_code);

            return $response;
        }

    }

    public static function get_subscription($subscriptio_id)
    {
        $payment_data = PaymentSetting::where('id', 1)->where('Tap_enabled', 1)->first();;
        if (!empty($payment_data)) {
            $secret_key = $payment_data->secret_key;

            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://api.tap.company/v2/subscription/v1/$subscriptio_id",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_POSTFIELDS => "{}",
                CURLOPT_HTTPHEADER => array(
                    "authorization: Bearer $secret_key"
                ),
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);
            self::create_log('', "https://api.tap.company/v2/subscription/v1/$subscriptio_id", 'Get subscription data', 'GET', 'Getting susbcription details for ' . $subscriptio_id, $response, 'get_subscription', '200');

            curl_close($curl);

            if ($err) {
                return false;
            } else {
                $response = json_decode($response);
                return $response;
            }
        } else {
            return false;
        }
    }

    public static function cancel_subscription($subscription_id)
    {
        $payment_data = PaymentSetting::where('id', 1)->where('Tap_enabled', 1)->first();;
        if (!empty($payment_data)) {
            $secret_key = $payment_data->secret_key;

            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://api.tap.company/v2/subscription/v1/$subscription_id",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "DELETE",
                CURLOPT_POSTFIELDS => "{}",
                CURLOPT_HTTPHEADER => array(
                    "authorization: Bearer $secret_key",
                    "content-type: application/json"
                ),
            ));
            $response = curl_exec($curl);
            self::create_log('', "https://api.tap.company/v2/subscription/v1/$subscription_id", 'Cancel Subscription', 'GET', 'Cancel subscription  for ' . $subscription_id, $response, 'cancel_subscription', '200');
            $err = curl_error($curl);
            curl_close($curl);
            if ($err) {
                return false;
            } else {
                $response = json_decode($response);
                if (!empty($response->id) && $response->status == 'CANCELLED') {
                    $Update = array(
                        'tap_subscription_id' => '',
                    );
                    User::where('id', auth()->user()->id)->update($Update);
                    return true;
                } else {
                    return false;
                }
            }
        } else {
            return false;
        }
    }
}