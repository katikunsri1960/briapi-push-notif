<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SettingParameter;
use App\Models\TagihanVAUnsri;
use App\Models\Connection\Tagihan;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Client;
use DateTime;
use DateTimeZone;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function index(){

        $data = SettingParameter::paginate(5)->withQueryString();

        return view('dashboard', ['data' => $data]);
    }

    public function tambah_bank(){

        return view('setting-parameter.index');

    }

    public function store_bank(Request $request){

        $validate = $request->validate([
            'kode_bank' => 'required',
            'nama_bank' => 'required',
            'client_id' => 'required',
            'client_secret' => 'required',
            'coorporate_code' => 'required',
            'private_key' => 'required',
            'url_sandbox' => 'required',
            'url_production' => 'required'
        ]);
        try{
            // Start transaction
            DB::beginTransaction();

            SettingParameter::create(['kode_bank' => $request->kode_bank, 'nama_bank' => $request->nama_bank, 'client_key' => $request->client_id, 'client_secret' => $request->client_secret, 'partner_service_id' => $request->coorporate_code, 'private_key' => $request->private_key, 'url_api_sandbox' => $request->url_sandbox, 'url_api_production' => $request->url_production]);

             // Commit transaction
             DB::commit();

            return redirect()->back()->with('success', "Token Berhasil di Tambahkan!");

        } catch (\Exception $e) {
            // Rollback transaction in case of error
            DB::rollBack();

            return redirect()->back()->with('error', "Terjadi kesalahan! " . $e->getMessage());
        }
    }
    
    public function get_token(){

        $data = SettingParameter::select('kode_bank', 'nama_bank')->distinct()->get();

        return view('setting-parameter.get-token', ['data' => $data]);
    }

    public function store_get_token(Request $request){

        $validate = $request->validate([
            'kode_bank' => 'required',
            
        ]);

        $data_token = $this->api_get_token($request->kode_bank);

        try{
            // Start transaction
            DB::beginTransaction();

            SettingParameter::where('kode_bank', $request->kode_bank)->update(['token' => $data_token['data']['accessToken']]);

             // Commit transaction
             DB::commit();

            return redirect()->back()->with('success', "Data Berhasil di Tambahkan!");

        } catch (\Exception $e) {
            // Rollback transaction in case of error
            DB::rollBack();

            return redirect()->back()->with('error', "Terjadi kesalahan! " . $e->getMessage());
        }

        
    }

    public function api_get_token($kode_bank)
    {
        $detail_bank = SettingParameter::where('kode_bank', $kode_bank)->first();
        if (!$detail_bank) {
            return response()->json(['error' => 'Bank not found'], 404);
        }

        // Create timestamp in the desired format
        $dateTime = new DateTime('now', new DateTimeZone('Asia/Jakarta'));
        $timestamp = $dateTime->format('Y-m-d\TH:i:s.vP'); // `.v` includes milliseconds

        // Create signature
        $string_signature = $detail_bank->client_key . '|' . $timestamp;
        $signatureHex = '';
        if (openssl_sign($string_signature, $signatureHex, $detail_bank->private_key, OPENSSL_ALGO_SHA256)) {
            $signature = base64_encode($signatureHex);
        } else {
            return response()->json(['error' => 'Failed to generate signature'], 500);
        }

        // Create headers
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

        // Make HTTP request
        $client = new Client();
        try {
            $response = $client->post($detail_bank->url_api_sandbox . '/snap/v1.0/access-token/b2b', [
                'headers' => $headers,
                'json' => $body, // Automatically converts array to JSON
            ]);

            // dd(json_decode($response->getBody()));

            // Return the response body
            return ['data' => json_decode($response->getBody(), true)];
            
        } catch (\Exception $e) {
            Log::error('API Token Error: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to get token'], 500);
        }
    }

    public function create_va($id_bank){

        try {
            // Fetch semesters order by `kode_periode`
            $semester = Tagihan::orderBy('kode_periode', 'desc')->first();

        } catch (\Exception $e) {
            // Return error if database connection fails
            return [
                'status' => 'error',
                'message' => 'Terjadi kesalahan!'. $e->getMessage(),
            ];
        }
        
        // Ensure `$semester` is not empty
        if (!$semester) {
            return redirect()->back()->with('error', 'Tidak ada data semester yang ditemukan!');
        }
        
        // Fetch data from TagihanVAUnsri based on semesters
        $data = TagihanVAUnsri::where('id_semester', $semester->kode_periode)->limit(5)->get();

        // dd($data);

        try{

            foreach($data as $d){

                try {
                    $this->api_create_va_bri(
                        $id_bank,
                        $d->customer_number,
                        $d->nama_mahasiswa,
                        $d->tagihan,
                        $d->tanggal_akhir_berlaku,
                        $d->id_semester
                    );
                } catch (\Exception $e) {
                    // Log the error for this particular record
                    \Log::error('Error creating VA for customer: ' . $d->customer_number . ' - ' . $e->getMessage());
                }

            }

            return redirect()->back()->with('success', "VA Berhasil di Tambahkan!");

        } catch (\Exception $e) {

            return redirect()->back()->with('error', "Terjadi kesalahan! " . $e->getMessage());
        }
    }

    public function api_create_va_bri($id_bank, $customer_number, $nama_mahasiswa, $tagihan, $tgl_akhir, $semester)
    {
        $detail_bank = SettingParameter::where('id', $id_bank)->first();
        if (!$detail_bank) {
            return response()->json(['error' => 'Bank details not found'], 404);
        }

        // Generate timestamp
        $dateTime = new DateTime('now', new DateTimeZone('Asia/Jakarta'));
        $timestamp = $dateTime->format('Y-m-d\TH:i:s.vP');

        // Generate virtual account number
        $va_number = $detail_bank->partner_service_id . $customer_number;

        // Prepare request body
        $requestBody = [
            "partnerServiceId" => $detail_bank->partner_service_id,
            "customerNo" => $customer_number,
            "virtualAccountNo" => $va_number,
            "virtualAccountName" => $nama_mahasiswa,
            "totalAmount" => [
                "value" => $tagihan,
                "currency" => "IDR",
            ],
            "expiredDate" => $tgl_akhir,
            "trxId" => $semester,
            "additionalInfo" => [
                "description" => "UKT UNSRI",
            ],
        ];

        // Convert request body to JSON and hash it
        $requestBodyJson = json_encode($requestBody, JSON_UNESCAPED_SLASHES);
        $hash = hash('sha256', $requestBodyJson);

        // Construct payload
        $httpMethod = 'POST';
        $requestPath = '/snap/v1.0/transfer-va/create-va';
        $payload = "{$httpMethod}:{$requestPath}:{$detail_bank->token}:{$hash}:{$timestamp}";

        // Generate HMAC-SHA512 signature
        $clientSecret = $detail_bank->client_secret; // Ensure this is set in your database
        $hmacSignature = hash_hmac('sha512', $payload, $clientSecret);

        // Prepare headers
        $headers = [
            'CHANNEL-ID' => 'BAPE',
            'X-SIGNATURE' => $hmacSignature,
            'X-TIMESTAMP' => $timestamp,
            'X-PARTNER-ID' => 'unsri',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $detail_bank->token,
        ];

        // Send the request
        $client = new \GuzzleHttp\Client();
        try {
            $response = $client->post($detail_bank->url_api_sandbox . $requestPath, [
                'headers' => $headers,
                'body' => $requestBodyJson,
            ]);
            // dd(json_decode($response->getBody()));

            return ['data' => json_decode($response->getBody(), true)];
        } catch (\Exception $e) {
            Log::error('VA Creation Error: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to create virtual account'], 500);
        }
    }
}
