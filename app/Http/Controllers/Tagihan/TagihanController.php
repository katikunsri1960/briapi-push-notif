<?php

namespace App\Http\Controllers\Tagihan;

use App\Models\TagihanVAUnsri;
use App\Models\Connection\Tagihan;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TagihanController extends Controller
{
    public function index(){

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
        
        // Fetch data for TagihanVAUnsri based on semesters
        $data = TagihanVAUnsri::where('id_semester', $semester->kode_periode)->get();
        
        // Return view with the fetched data
        return view('tagihan.index', [
            'data' => $data,
            'semester' => $semester,
        ]);
        
    }

    public function get_tagihan_unsri(Request $request, $semester){

        $db = new TagihanVAUnsri();

        $get_data = $db->get_data_tagihan_mahasiswa($semester);
        // dd($get_data);
        return redirect()->back()->with($get_data['status'], $get_data['message']);

    }
}
