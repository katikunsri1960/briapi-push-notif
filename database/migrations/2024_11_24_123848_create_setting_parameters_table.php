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
        Schema::create('setting_parameters', function (Blueprint $table) {
            $table->id();
            $table->string('nama_bank');
            $table->string('kode_bank');
            $table->string('client_key');
            $table->string('partner_id');
            $table->string('signature');
            $table->string('partner_service_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('setting_parameters');
    }
};
