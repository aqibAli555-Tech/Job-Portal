<?php

namespace App\Helpers;

use App\Models\PaypalLog;
use GuzzleHttp\Client;

class PayPal
{
    public static function sendPayout($receiverEmail, $withdrawData)
    {
        if(app()->environment() !== 'local')
        {
            $clientId = '';
            $clientSecret = '';
        }else{
            $clientId = '';
            $clientSecret = '';
        }
        try {
            $client = new Client();

            $authResponse = $client->post('url', [
                'auth' => [$clientId, $clientSecret],
                'form_params' => ['grant_type' => 'client_credentials'],
            ]);
            $data = json_decode($authResponse->getBody(), true);
            $accessToken = $data['access_token'] ?? null;
    
            if (!$accessToken) {
                return ['success' => false, 'message' => 'Unable to obtain access token from PayPal.'];
            }

            $amount = $withdrawData->amount;
            $name = $withdrawData->user->name;

            $requestData = [
                'sender_batch_header' => [
                    'sender_batch_id' => uniqid(),
                    'email_subject' => "Withdraw Request Approved",
                    'email_message' => "{$name} withdraw request of amount \${$amount} is approved by the admin.",
                ],
                'items' => [[
                    'recipient_type' => 'EMAIL',
                    'amount' => ['value' => $amount, 'currency' => 'USD'],
                    'receiver' => $receiverEmail,
                    'note' => "{$name} withdraw request of amount \${$amount} is approved by the admin.",
                    'sender_item_id' => uniqid(),
                ]],
            ];

            $payoutResponse = $client->post('https://api-m.sandbox.paypal.com/v1/payments/payouts', [
                'headers' => [
                    'Authorization' => "Bearer {$accessToken}",
                    'Content-Type' => 'application/json',
                ],
                'json' => $requestData,
            ]);

            $responseData = json_decode($payoutResponse->getBody(), true);
            $paypalId = $responseData['batch_header']['payout_batch_id'];

            PayPalLog::create([
                'user_id' => $withdrawData->user_id,
                'withdraw_request_id' => $withdrawData->id,
                'paypal_id' => $paypalId,
                'request_data' => $requestData,
                'response_data' => $responseData,
            ]);

            return ['success' => true, 'data' => $responseData];

        } catch (\GuzzleHttp\Exception\RequestException $e) {
            $errorResponse = $e->hasResponse() ? json_decode($e->getResponse()->getBody()->getContents(), true) : null;

            PayPalLog::create([
                'user_id' => $withdrawData->user_id,
                'withdraw_request_id' => $withdrawData->id,
                'request_data' => $requestData ?? [],
                'response_data' => $errorResponse ?? ['error' => $e->getMessage()],
            ]);

            $errorMessage = $errorResponse['message'] ?? 'Failed to process PayPal payout.';
            return ['success' => false, 'message' => $errorMessage];
    
        } catch (\Exception $e) {
            
            PaypalLog::create([
                'user_id' => $withdrawData->user_id,
                'withdraw_request_id' => $withdrawData->id,
                'request_data' => $requestData ?? [],
                'response_data' => ['error' => $e->getMessage()],
            ]);

            return ['success' => false, 'message' => $e->getMessage() ?: 'Failed to process PayPal payout.'];
        }
    }

    public static function checkPayoutStatus($payoutBatchId)
    {
        if(app()->environment() !== 'local')
        {
            $clientId = '';
            $clientSecret = '';
        }else{
            $clientId = '';
            $clientSecret = '';
        }
        try {
            $client = new \GuzzleHttp\Client();

            $authResponse = $client->post('url', [
                'auth' => [$clientId, $clientSecret],
                'form_params' => ['grant_type' => 'client_credentials'],
            ]);

            $data = json_decode($authResponse->getBody(), true);
            $accessToken = $data['access_token'] ?? null;
            if (!$accessToken) {
                return ['success' => false, 'message' => 'Unable to obtain access token.'];
            }
            
            $response = $client->get("https://api-m.sandbox.paypal.com/v1/payments/payouts/{$payoutBatchId}", [
                'headers' => [
                    'Authorization' => "Bearer {$accessToken}",
                    'Content-Type' => 'application/json',
                ],
            ]);
            $responseData = json_decode($response->getBody(), true);

            return ['success' => true, 'data' => $responseData];

        } catch (\GuzzleHttp\Exception\RequestException $e) {
            $error = $e->hasResponse() ? json_decode($e->getResponse()->getBody()->getContents(), true) : ['error' => $e->getMessage()];
            return ['success' => false, 'message' => $error];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

}
