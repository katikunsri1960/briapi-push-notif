<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentNotifications extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'additional_info' => 'array',
        'trx_date_time' => 'datetime',
    ];

    public static function createNotification($data)
    {
        return self::create([
            'bank_info_id' => $data['bank_info_id'],
            'partner_service_id' => $data['partner_service_id'],
            'customer_no' => $data['customer_no'],
            'virtual_account_no' => $data['virtual_account_no'],
            'payment_request_id' => $data['payment_request_id'],
            'trx_date_time' => $data['trx_date_time'],
            'additional_info' => $data['additional_info'],
        ]);
    }
}
