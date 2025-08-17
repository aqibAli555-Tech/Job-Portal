<?php

namespace App\Http\Controllers\Install\Traits\Install;

use App\Helpers\Curl;
use App\Helpers\Ip;
use Exception;
use PulkitJalan\GeoIP\Facades\GeoIP;

trait ApiTrait
{
    /**
     * @return mixed|null
     */
    private static function getCountryCodeFromIPAddr()
    {
        if (isset($_COOKIE['ip_country_code'])) {
            $countryCode = $_COOKIE['ip_country_code'];
        } else {
            // Localize the user's country
            try {
                $ipAddr = Ip::get();

                GeoIP::setIp($ipAddr);
                $countryCode = GeoIP::getCountryCode();

                if (!is_string($countryCode) or strlen($countryCode) != 2) {
                    return null;
                }
            } catch (Exception $e) {
                return null;
            }

            // Set data in cookie
            if (isset($_COOKIE['ip_country_code'])) {
                unset($_COOKIE['ip_country_code']);
            }
            setcookie('ip_country_code', $countryCode);
        }

        return $countryCode;
    }

    /**
     * IMPORTANT: Do not change this part of the code to prevent any data losing issue.
     *
     * @param $purchaseCode
     * @return false|mixed|string
     */
    private function purchaseCodeChecker($purchaseCode)
    {
        $apiUrl = config('larapen.core.purchaseCodeCheckerUrl') . $purchaseCode . '&item_id=' . config('larapen.core.itemId');
        $data = Curl::fetch($apiUrl);

        // Check & Get cURL error by checking if 'data' is a valid json
        // if (!isValidJson($data)) {
        // 	$data = json_encode(['valid' => false, 'message' => 'Invalid purchase code. ' . strip_tags($data)]);
        // }

        // Format object data
        $data = json_decode($data);

        // Check if 'data' has the valid json attributes
        // if (!isset($data->valid) || !isset($data->message)) {
        // 	$data = json_encode(['valid' => false, 'message' => 'Invalid purchase code. Incorrect data format.']);
        // 	$data = json_decode($data);
        // }

        return $data;
    }
}
