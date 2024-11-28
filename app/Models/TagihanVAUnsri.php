<?php

namespace App\Models;

use App\Models\Connection\Tagihan;
use Illuminate\Database\Eloquent\Model;

class TagihanVAUnsri extends Model
{
    protected $table = 'tagihan_va_unsris';

    public function get_data_tagihan_mahasiswa($semester){

        try {
            $data = Tagihan::where('kode_periode', $semester)->where('is_tagihan_aktif', 1)->get();

            DB::beginTransaction();

            foreach ($data as $item) {

                $customer_number = generate_customer_number($item->nim, 14);

                $this->create([
                    'customer_number' => $customer_number,
                    'nim' => $item->nomor_pembayaran,
                    'nama_mahasiswa' => $item->nama,
                    'id_fakultas' => $item->kode_fakultas,
                    'nama_fakultas' => $item->nama_fakultas,
                    'id_prodi' => $item->kode_prodi,
                    'nama_jenjang_didik' => $item->strata,
                    'nama_program_studi' => $item->nama_prodi,
                    'id_periode_masuk' => $item->angkatan,
                    'tipe_ukt' => 0,
                    'tagihan' => $item->total_nilai_tagihan,
                    'status_tagihan' => 0,
                    'tanggal_mulai_berlaku' => $waktu_berlaku,
                    'tanggal_akhir_berlaku' => $waktu_berakhir,
                    'id_semester' => $item->kode_periode,
                    'nama_semester' => $item->nama_periode
                ]);
            }

            DB::commit();

            $result = [
                'status' => 'success',
                'message' => 'Data Tagihan Berhasil di Tarik!',
            ];

            return $result;

        } catch (\Exception $e) {

            DB::rollBack();

            $result = [
                'status' => 'error',
                'message' => 'Terjadi kesalahan! '. $e->getMessage(),
            ];

            return $result;
        }
    }

    function generate_customer_number($nim, $n) {

        $length = strlen($nim);
        $indexToExclude = $length - $n;

        return substr($nim, 0, $indexToExclude) . substr($nim, $indexToExclude + 1);
    }
}
