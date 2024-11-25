<?php

namespace App\Http\Controllers\Tagihan;

use App\Models\TagihanVAUnsri;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TagihanController extends Controller
{
    public function index(){

        $semester='20241';

        $data = TagihanVAUnsri::where('id_semester', $semester)->get();

        return view('tagihan.index', ['data' => $data, 'semester' => $semester]);
    }

    public function get_tagihan_unsri(Request $request){

        $validate_data = $request->validate([
            'semester' => 'required',
            'tgl_mulai' => 'required',
            'tgl_akhir' => 'required'
        ]);

        $db = new TagihanVAUnsri();

        $get_data = $db->get_data_tagihan_mahasiswa($request->semester, $request->tgl_mulai, $request->tgl_akhir);
        // dd($get_data);
        return redirect()->back()->with($get_data['status'], $get_data['message']);

    }

    public function get_token_bri(){

        $detail_bank = SettingParameter::where('kode_bank', 'BRI')->first();

        $dateTime = new DateTime('now', new DateTimeZone('Asia/Jakarta')); // Set the desired timezone
        $time_stamp = $dateTime->format('Y-m-d\TH:i:s.vP'); // `.v` includes milliseconds

        $client = new Client();
        $headers = [
        'X-SIGNATURE' => '"'.$detail_bank->signature.'"',
        'X-CLIENT-KEY' => '"'.$detail_bank->client_key.'"',
        'X-TIMESTAMP' => '"'.$time_stamp.'"',
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
        'X-SIGNATURE' => '"'.$detail_bank->signature.'"',
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
