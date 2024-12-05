<?php

namespace App\Services;

use App\Models\SettingParameter;
use DateTime;
use DateTimeZone;
use GuzzleHttp\Client;
use Carbon\Carbon;

class Snap {

    private $kode_bank;

    function __construct($kode_bank) {
        $this->kode_bank = $kode_bank;
    }

    public function getToken()
    {
        $detail_bank = SettingParameter::where('kode_bank', $this->kode_bank)->first();

        if (!$detail_bank) {
            return [
                'status' => 'error',
                'code' => 404,
                'message' => 'Kode bank tidak ditemukan'
            ];
        }

         // Create timestamp in the desired format
         $dateTime = new DateTime('now', new DateTimeZone('Asia/Jakarta'));
         $timestamp = $dateTime->format('Y-m-d\TH:i:s.vP'); // `.v` includes milliseconds

         $string_signature = $detail_bank->client_key . '|' . $timestamp;
         $signatureHex = '';

         if (openssl_sign($string_signature, $signatureHex, $detail_bank->private_key, OPENSSL_ALGO_SHA256)) {
             $signature = base64_encode($signatureHex);
         } else {
             return [
                'status' => 'error',
                'code' => 500,
                'message' => 'Failed to generate signature'
             ];
         }

         $headers = [
            'X-SIGNATURE' => $signature,
            'X-CLIENT-KEY' => $detail_bank->client_key,
            'X-TIMESTAMP' => $timestamp,
            'Content-Type' => 'application/json',
        ];

        // Create request body
        $body = [
            "grantType" => "client_credentials",
        ];

        $client = new Client();

        try {
            $response = $client->post($detail_bank->url_api_sandbox . '/snap/v1.0/access-token/b2b', [
                'headers' => $headers,
                'json' => $body, // Automatically converts array to JSON
            ]);

            // dd(json_decode($response->getBody()));
            $result = json_decode($response->getBody(), true);
            // Return the response body
            return [
                'status' => 'success',
                'code' => 200,
                'data' => $result
            ];

        } catch (\Exception $e) {

            return [
                'status' => 'error',
                'code' => 500,
                'message' => 'Failed to get token! ' . $e->getMessage()
            ];
        }
    }
}
