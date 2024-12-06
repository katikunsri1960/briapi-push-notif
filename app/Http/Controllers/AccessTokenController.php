<?php

namespace App\Http\Controllers;

use App\Models\AccessToken;
use App\Models\BankInfo;
use App\Models\PaymentNotifications;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AccessTokenController extends Controller
{
    public function getToken(Request $request)
    {
        // Ambil header
        $clientId = $request->header('X-CLIENT-KEY');
        $signature = $request->header('X-SIGNATURE');
        $timestamp = $request->header('X-TIMESTAMP');

        // Validasi keberadaan header
        if (!$clientId || !$signature || !$timestamp) {
            return response()->json(['message' => 'missingRequiredHeaders'], 400);
        }

        // Validasi format timestamp (ISO 8601)
        if (!preg_match('/\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}.\d{3}(\+|\-)\d{2}:\d{2}/', $timestamp)) {
            return response()->json([
                'responseCode' => '4003402',
                'responseMessage' => 'invalidTimestampFormat',
            ], 400);
        }

        // Cari bank info berdasarkan Client ID
        $bankInfo = BankInfo::where('client_id', $clientId)->first();
        if (!$bankInfo) {
            return response()->json([
                "responseCode" => "4017300",
                "responseMessage" => "Unauthorized. stringToSign"
            ], 401);
        }

        // Cek validitas timestamp (maksimal perbedaan 5 menit)
        // $requestTime = Carbon::parse($timestamp);
        // $currentTime = now();
        // if ($currentTime->diffInSeconds($requestTime) > 300) { // 5 menit
        //     return response()->json([
        //         "responseCode" => "4037300",
        //         "responseMessage" => "timestampExpired"
        //     ], 403);
        // }

        // Buat payload untuk validasi signature
        $payload = $clientId . '|' . $timestamp;

        // Validasi Signature RSA
        $publicKey = openssl_pkey_get_public($bankInfo->rsa_public_key);

        if (!$publicKey) {
            return response()->json([
                "responseCode" => "4017300",
                "responseMessage" => "Unauthorized. stringToSign"
            ], 401);
        }

        $isValidSignature = openssl_verify(
            $payload, // Payload asli
            base64_decode($signature), // Signature yang dikirimkan
            $publicKey,
            OPENSSL_ALGO_SHA256
        );

        if ($isValidSignature !== 1) { // 1 berarti valid, 0 tidak valid, -1 error
            return response()->json([
                "responseCode" => "4017300",
                "responseMessage" => "Unauthorized. stringToSign"
            ], 401);
        }

        // Generate Access Token
        $accessToken = Str::random(32);

        // Simpan token ke database
        AccessToken::create([
            'bank_info_id' => $bankInfo->id,
            'access_token' => $accessToken,
            'expires_at' => now()->addMinutes(15), // 15 menit sesuai SNAP BI
        ]);

        // Response sesuai standar SNAP BI
        return response()->json([
            'accessToken' => $accessToken,
            'tokenType' => 'BearerToken',
            'expiresIn' => 899, // 15 menit dalam detik (899 detik sesuai SNAP BI)
        ], 200);
    }

    public function notifyPaymentIntrabank(Request $request)
    {
        // Ambil header
        $authorization = $request->header('Authorization');
        $timestamp = $request->header('X-TIMESTAMP');
        $signature = $request->header('X-SIGNATURE');
        $contentType = $request->header('Content-Type');
        $partnerId = $request->header('X-PARTNER-ID');
        $channelId = $request->header('CHANNEL-ID');
        $externalId = $request->header('X-EXTERNAL-ID');

        // Validasi keberadaan header
        if (!$authorization || !$timestamp || !$signature || !$contentType || !$partnerId || !$channelId || !$externalId) {
            return response()->json([
                'responseCode' => '4003402',
                'responseMessage' => 'missingRequiredHeaders'
            ], 400);
        }

        // Validasi format timestamp (ISO 8601 dengan milidetik)
        if (!preg_match('/\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}\.\d{3}(\+|\-)\d{2}:\d{2}/', $timestamp)) {
            return response()->json([
                'responseCode' => '4003402',
                'responseMessage' => 'invalidTimestampFormat',
                'timestamp' => $timestamp
            ], 400);
        }

        // Validasi Content-Type
        if ($contentType !== 'application/json') {
            return response()->json([
                'responseCode' => '4003401',
                'responseMessage' => 'Invalid Field Format. invalid (ContentType)'
            ], 400);
        }

        // Ambil token dari header Authorization
        if (!preg_match('/Bearer\s(\S+)/', $authorization, $matches)) {
            return response()->json(['message' => 'invalidAuthorizationFormat'], 400);
        }
        $accessToken = $matches[1];

        // Cari bank info berdasarkan Partner ID
        $bankInfo = BankInfo::where('partner_id', $partnerId)->first();

        if (!$bankInfo) {
            return response()->json([
                "responseCode" => "4043416",
                "responseMessage" => "Partner not found",
            ], 401);
        }

        // Validasi Access Token
        $token = AccessToken::where('access_token', $accessToken)
            ->where('bank_info_id', $bankInfo->id)
            // ->where('expires_at', '>', now())
            ->first();

        if (!$token) {
            return response()->json([
                "responseCode" => "4012701",
                "responseMessage" => "Invalid Token (B2B)",
                // 'accessToken' => $accessToken
            ], 401);
        }

        // cek apakah token sudah expired
        if ($token->expires_at < now()) {
            return response()->json([
                "responseCode" => "4012701",
                "responseMessage" => "Invalid Token (B2B)",
                // 'accessToken' => $accessToken
            ], 401);
        }

        // Cek validitas timestamp (maksimal perbedaan 5 menit)
        // $requestTime = Carbon::parse($timestamp);
        // $currentTime = now();
        // if ($currentTime->diffInSeconds($requestTime) > 300) { // 5 menit
        //     return response()->json([
        //         "responseCode" => "4037300",
        //         "responseMessage" => "timestampExpired"
        //     ], 403);
        // }

        // Buat payload untuk validasi signature
        $httpMethod = $request->method();
        $requestPath = $request->path();
        $requestBody = json_encode($request->all());
        $hash = hash('sha256', $requestBody);
        $payload = $httpMethod . ':/' . $requestPath . ':' . $accessToken . ':' . $hash . ':' . $timestamp;

        // Validasi Signature HMAC_SHA512
        $calculatedSignature = hash_hmac('sha512', $payload, $bankInfo->client_secret);
        if ($signature !== $calculatedSignature) {
            return response()->json([
                "responseCode" => "4013401",
                "responseMessage" => "Unauthorized. Verify Client Secret Fail.",
            ], 401);
        }

        // Proses notifikasi pembayaran
        // Simpan data notifikasi ke database atau lakukan tindakan lain yang diperlukan
        // $notification = $request->all();

        // $notification = PaymentNotifications::createNotification($request->all());
        $dataInsert = [
            'bank_info_id' => $bankInfo->id,
            'partner_service_id' => $request->partnerServiceId,
            'customer_no' => $request->customerNo,
            'virtual_account_no' => $request->virtualAccountNo,
            'payment_request_id' => $request->paymentRequestId,
            'trx_date_time' => $request->trxDateTime,
            'additional_info' => $request->additionalInfo,
        ];
        try {
            $notification = PaymentNotifications::create($dataInsert);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'responseCode' => '5003400',
                'responseMessage' => 'Internal Server Error. ' . $th->getMessage(),
                'data' => $dataInsert,
            ], 500);
        }

        // Response sesuai standar SNAP BI
        return response()->json([
            'responseCode' => '2003400',
            'responseMessage' => 'Successful',
            'virtualAccountData' => $request->all(),
        ], 200);
    }
}
