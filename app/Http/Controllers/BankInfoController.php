<?php

namespace App\Http\Controllers;

use App\Models\BankInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BankInfoController extends Controller
{
    public function index(Request $request)
    {
        $bankInfo = BankInfo::select('id', 'client_id', 'client_secret', 'rsa_public_key', 'partner_id')->get();

        return view('bank-info.index', [
            'bankInfo' => $bankInfo
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'client_id' => 'required|string',
            'client_secret' => 'required|string',
            'rsa_public_key' => 'required|string',
            'partner_id' => 'required|string',
        ]);

        $data['channel_id'] = 'BAPE';

        try {
            DB::beginTransaction();

            BankInfo::create($data);

            DB::commit();

            return redirect()->route('bank-info')->with('success', 'Bank info has been saved');
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();

            return redirect()->route('bank-info')->with('error', 'Bank info failed to save '. $th->getMessage());
        }
    }

    public function destroy(BankInfo $bankInfo)
    {
        try {
            DB::beginTransaction();

            $bankInfo->delete();

            DB::commit();

            return redirect()->route('bank-info')->with('success', 'Bank info has been deleted');
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();

            return redirect()->route('bank-info')->with('error', 'Bank info failed to delete '. $th->getMessage());
        }
    }
}
