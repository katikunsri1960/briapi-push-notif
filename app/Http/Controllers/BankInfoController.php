<?php

namespace App\Http\Controllers;

use App\Models\BankInfo;
use Illuminate\Http\Request;

class BankInfoController extends Controller
{
    public function index(Request $request)
    {
        $bankInfo = BankInfo::select('id', 'client_id', 'client_secret', 'rsa_public_key', 'partner_id')->get();

        return view('bank-info.index', [
            'bankInfo' => $bankInfo
        ]);
    }
}
