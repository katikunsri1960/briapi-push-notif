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
        Schema::create('payment_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bank_info_id')->constrained()->onDelete('cascade');
            $table->string('partner_service_id');
            $table->string('customer_no');
            $table->string('virtual_account_no');
            $table->string('payment_request_id');
            $table->dateTime('trx_date_time');
            $table->json('additional_info');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_notifications');
    }
};
