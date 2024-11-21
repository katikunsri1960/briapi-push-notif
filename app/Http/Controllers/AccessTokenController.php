<?php

namespace App\Http\Controllers;

use App\Models\AccessToken;
use App\Models\BankInfo;
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
            return response()->json(['message' => 'invalidTimestampFormat'], 400);
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
        $requestTime = Carbon::parse($timestamp);
        $currentTime = now();
        if ($currentTime->diffInSeconds($requestTime) > 300) { // 5 menit
            return response()->json([
                "responseCode" => "4037300",
                "responseMessage" => "timestampExpired"
            ], 403);
        }

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
}
