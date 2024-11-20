<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\AccessToken;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AccessTokenController extends Controller
{
    public function getToken(Request $request)
    {
        // Ambil header
        $clientKey = $request->header('X-CLIENT-KEY');
        $signature = $request->header('X-SIGNATURE');
        $timestamp = $request->header('X-TIMESTAMP');

        // Validasi keberadaan header
        if (!$clientKey || !$signature || !$timestamp) {
            return response()->json(['message' => 'missingRequiredHeaders'], 400);
        }

        // Cari client berdasarkan Client Key
        $client = Client::where('client_secret', $clientKey)->first();
        if (!$client) {
            return response()->json(['message' => 'invalidClientKey'], 401);
        }

        // Validasi Signature RSA
        $payload = json_encode(['grantType' => $request->grantType], JSON_UNESCAPED_SLASHES);
        $publicKey = openssl_pkey_get_public($client->rsa_public_key);

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
            'client_id' => $client->id,
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
