<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SettingParameter extends Model
{
    public function index(){

        $auth_bank='BRI';

        $data = SettingParameter::where('kode_bank', $auth_bank)->get();

        return view('dashboard', ['data' => $data]);
    }
}
