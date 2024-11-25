<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tagihan_va_unsris', function (Blueprint $table) {
            $table->id();
            $table->string('nim');
            $table->string('nama_mahasiswa');
            $table->string('id_fakultas');
            $table->string('nama_fakultas');
            $table->string('id_prodi');
            $table->string('nama_jenjang_didik');
            $table->string('nama_program_studi');
            $table->string('id_periode_masuk');
            $table->string('tipe_ukt')->nullable();
            $table->string('tagihan');
            $table->string('customer_number');
            $table->date('tanggal_mulai_berlaku');
            $table->date('tanggal_akhir_berlaku');
            $table->string('id_semester');
            $table->string('nama_semester');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tagihan_va_unsris');
    }
};
