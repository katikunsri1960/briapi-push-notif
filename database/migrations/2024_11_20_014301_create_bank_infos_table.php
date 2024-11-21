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
        Schema::create('bank_infos', function (Blueprint $table) {
            $table->id();
            $table->string('client_id')->unique(); // Client ID
            $table->string('client_secret'); // Client Secret
            $table->string('channel_id'); // Channel ID
            $table->string('partner_id'); // Partner ID
            $table->text('rsa_public_key'); // Public Key RSA
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bank_infos');
    }
};
