<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SettingParameter;

class DashboardController extends Controller
{
    public function index(){

        $data = SettingParameter::get();

        return view('dashboard', ['data' => $data]);
    }
}
