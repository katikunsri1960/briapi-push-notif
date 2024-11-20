<?php

namespace App\Http\Controllers;

use App\Models\AccessToken;
use App\Models\BankInfo;
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

        // Cari bank info berdasarkan Client ID
        $bankInfo = BankInfo::where('client_id', $clientId)->first();
        if (!$bankInfo) {
            return response()->json(['message' => 'invalidClientId'], 401);
        }

        // Validasi Signature RSA
        $payload = json_encode(['grantType' => $request->grantType], JSON_UNESCAPED_SLASHES);
        $publicKey = openssl_pkey_get_public($bankInfo->rsa_public_key);

        $isValidSignature = openssl_verify(
            $payload . $timestamp,
            base64_decode($signature),
            $publicKey,
            OPENSSL_ALGO_SHA256
        );

        if (!$isValidSignature) {
            return response()->json(['message' => 'invalidSignature'], 403);
        }

        // Generate Access Token
        $accessToken = Str::random(64);

        // Simpan token
        AccessToken::create([
            'bank_info_id' => $bankInfo->id,
            'access_token' => $accessToken,
            'expires_at' => now()->addMinutes(30),
        ]);

        // Response camelCase
        return response()->json([
            'accessToken' => $accessToken,
            'expiresAt' => now()->addMinutes(30)->toISOString(),
        ]);
    }
}
