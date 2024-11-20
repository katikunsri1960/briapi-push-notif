<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class DevController extends Controller
{
    public function index()
    {
        return view('index');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'client_id' => 'required|string',
            'client_secret' => 'required|string',
            'partner_id' => 'required|string',
            'rsa_public_key' => 'required|string',
            'ip_address' => 'nullable|string',
        ]);

        $client = Client::create($data);

        return redirect()->route('dev.index')->with('success', 'Client created successfully');
    }
}
