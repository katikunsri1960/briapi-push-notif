<?php

namespace App\Models;

use App\Models\Connection\Tagihan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TagihanVAUnsri extends Model
{
    protected $table = 'tagihan_va_unsris';
    protected $guarded = ['id'];

    public function get_data_tagihan_mahasiswa($semester)
    {
        ini_set('max_execution_time', 60);
        
        try {
            // Fetch active Tagihan data for the specified semester
            $data = Tagihan::where('kode_periode', $semester)
                ->where('is_tagihan_aktif', 1)
                ->get();

            // Handle case where no data is found
            if ($data->isEmpty()) {
                return [
                    'status' => 'error',
                    'message' => 'Tidak ada data tagihan yang ditemukan untuk semester ini.',
                ];
            }

            // Start transaction
            DB::beginTransaction();

            foreach ($data as $item) {

                // Generate customer number
                $customer_number = $this->generate_customer_number($item->nomor_pembayaran, 14);

                // Data to update or create
                $updateData = [
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
                    'tanggal_mulai_berlaku' => $item->waktu_berlaku,
                    'tanggal_akhir_berlaku' => $item->waktu_berakhir,
                    'id_semester' => $item->kode_periode,
                    'nama_semester' => $item->nama_periode,
                ];

                // Use updateOrCreate for create or update
                $this->updateOrCreate(['customer_number' => $customer_number, 'kode_periode' => $semester], $updateData);
            }

            // Commit transaction
            DB::commit();

            return [
                'status' => 'success',
                'message' => 'Data Tagihan Berhasil di Tarik!',
            ];
        } catch (\Exception $e) {
            // Rollback transaction in case of error
            DB::rollBack();

            return [
                'status' => 'error',
                'message' => 'Terjadi kesalahan! ' . $e->getMessage(),
            ];
        }
    }


    private function generate_customer_number($nim, $n) {

        $length = strlen($nim);
        $indexToExclude = $length - $n;
        if($length != 14){
            $customer_num = $nim;
        }else{
            $customer_num = substr($nim, 0, $indexToExclude) . substr($nim, $indexToExclude + 1);
        }

        return $customer_num;
    }
}
