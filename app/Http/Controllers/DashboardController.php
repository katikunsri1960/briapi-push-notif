<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SettingParameter;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(){

        $data = SettingParameter::get();

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
            'private_key' => 'required'
        ]);
        try{
            // Start transaction
            DB::beginTransaction();

            SettingParameter::create(['kode_bank' => $request->kode_bank, 'nama_bank' => $request->nama_bank, 'client_key' => $request->client_id, 'client_secret' => $request->client_secret, 'partner_service_id' => $request->coorporate_code, 'private_key' => $request->private_key]);

             // Commit transaction
             DB::commit();

            return redirect()->back()->with('success', "Data Berhasil di Tambahkan!");

        } catch (\Exception $e) {
            // Rollback transaction in case of error
            DB::rollBack();

            return redirect()->back()->with('error', "Terjadi kesalahan! " . $e->getMessage());
        }
    }

    public function get_token(Request $request){

        $detail_bank = SettingParameter::where('kode_bank', $request->kode_bank)->first();
        $dateTime = new DateTime('now', new DateTimeZone('Asia/Jakarta')); // Set the desired timezone
        $timestamp = $dateTime->format('Y-m-d\TH:i:s.vP'); // `.v` includes milliseconds

        // Buat signature untuk validasi signature
        $string_signature = $detail_bank->clientId . '|' . $timestamp;
        $signature = 


        $client = new Client();
        $headers = [
        'X-SIGNATURE' => '"'.$signature.'"',
        'X-CLIENT-KEY' => '"'.$detail_bank->client_id.'"',
        'X-TIMESTAMP' => '"'.$timestamp.'"',
        'Content-Type' => 'application/json'
        ];
        $body = '{
        "grantType": "client_credentials"
        }';
        $request = new Request('POST', 'https://sandbox.partner.api.bri.co.id/snap/v1.0/access-token/b2b', $headers, $body);
        $res = $client->sendAsync($request)->wait();

        return $res->getBody();
    }

    public function create_va_bri($customer_number, $nama_mahasiswa, $tagihan, $tgl_akhir, $semester){

        $detail_bank = SettingParameter::where('kode_bank', 'BRI')->first();

        $dateTime = new DateTime('now', new DateTimeZone('Asia/Jakarta')); // Set the desired timezone
        $time_stamp = $dateTime->format('Y-m-d\TH:i:s.vP'); // `.v` includes milliseconds
        $va_number = $partner_service_id.$customer_number;

        $client = new Client();
        $headers = [
        'CHANNEL-ID' => 'BAPE',
        'X-SIGNATURE' => '"'.$signature.'"',
        'X-TIMESTAMP' => '"'.$time_stamp.'"',
        'X-PARTNER-ID' => 'unsri',
        'Content-Type' => 'application/json',
        'Authorization' => 'Bearer '.'"'.$token.'"'
        ];
        $body = '{
        "partnerServiceId": "   '.$detail_bank->partner_service_id.'",
        "customerNo": "'.$customer_number.'",
        "virtualAccountNo": "   '.$va_number.'",
        "virtualAccountName": "'.$nama_mahasiswa.'",
        "totalAmount": {
            "value": "'.$tagihan.'",
            "currency": "IDR"
        },
        "expiredDate": "'.$tgl_akhir.'",
        "trxId": "'.$semester.'",
        "additionalInfo": {
            "description": "UKT UNSRI"
        }
        }';
        $request = new Request('POST', 'https://sandbox.partner.api.bri.co.id/snap/v1.0/transfer-va/create-va', $headers, $body);
        $res = $client->sendAsync($request)->wait();

        return $res->getBody();
    }
}
