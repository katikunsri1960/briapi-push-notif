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
            $table->string('client_key'); //client id
            $table->string('client_secret');
            $table->string('partner_service_id'); //Coorporate Code
            $table->text('private_key'); //RSA Private Key
            $table->text('url_api_sandbox'); //URL API Development
            $table->text('url_api_production'); //URL API Production
            $table->text('token')->nullable(); //Temporary Token
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
