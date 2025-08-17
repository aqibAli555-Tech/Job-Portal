<?php

namespace App\Helpers;

class Myfatoorahv2

{

    public $token;
    public $return_url;
    public $error_ret_url;
    public $basURL;
    public $merchantCode;
    public $is_live = 0;

    public static function getPaymentLink($cust_details, $price, $order_id, $return_url, $error_ret_url, $PaymentMethodId = "", $InvoiceItems = [], $SupplierCode = "")
    {
        $cust_details['mobile'] = $str = substr($cust_details['mobile'], 0, 8);

        $token = '';

        $post_string_array = [
            'PaymentMethodId' => $PaymentMethodId,
            'CustomerName' => (string)$cust_details['name'],
            'DisplayCurrencyIso' => "KWD",
            'MobileCountryCode' => "",
            'CustomerMobile' => $cust_details['mobile'],
            'CustomerEmail' => $cust_details['email'],
            'InvoiceValue' => $price,
            'CallBackUrl' => $return_url,
            'ErrorUrl' => $error_ret_url,
            'Language' => "en",
            'CustomerReference' => $SupplierCode,
            'CustomerCivilId' => "",
            'UserDefinedField' => $order_id . "_" . $price,
            'ExpireDate' => '',
            'SupplierCode' => $SupplierCode,
            'CustomerAddress' => [
                'Block' => '',
                'Street' => '',
                'HouseBuildingNo' => '',
                'Address' => '',
                'AddressInstructions' => ''
            ],
            'InvoiceItems' => $InvoiceItems,
        ];

        $post_string = json_encode($post_string_array);
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "$basURL/v2/ExecutePayment",
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $post_string,
            CURLOPT_HTTPHEADER => array("Authorization: Bearer $token", "Content-Type: application/json"),
        ));

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            return $err;
        } else {
            $response = json_decode($response, true);
            if ($response['IsSuccess']) {
                return $response['Data']['PaymentURL'];
            } else {
                echo "<pre>";
                print_r($response);
                die;
            }
        }
    }

    public static function paymentdetails($payment_id)

    {

        $token = "";

        $data = array(
            'KeyType' => 'paymentId',
            'Key' => "$payment_id" //the callback paymentID
        );

        $fields = json_encode($data);
        $curl = curl_init($url);
        curl_setopt_array($curl, array(
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $fields,
            CURLOPT_HTTPHEADER => array("Authorization: Bearer $token", 'Content-Type: application/json'),
            CURLOPT_RETURNTRANSFER => true,
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
            return false;
        } else {
            $response = json_decode($response, true);
            if (!empty($response['IsSuccess'])) {
                return $response['Data'];
            } else {
                return false;
            }
        }
    }

    public function Init($price)

    {
        $this->merchantCode = '';

        $this->token = "";

        $this->basURL = "https://apitest.myfatoorah.com";

        $token = $this->token;
        $basURL = $this->basURL;
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "$basURL/v2/InitiatePayment",
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "{\"InvoiceAmount\": $price,\"CurrencyIso\": \"KWD\"}",
            CURLOPT_HTTPHEADER => array("Authorization: Bearer $token", "Content-Type: application/json"),
        ));

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            return false;
        } else {
            $response = json_decode($response, true);
            if (!empty($response['IsSuccess'])) {
                return $response['Data']['PaymentMethods'];
            } else {
                return false;
            }
        }
    }
}