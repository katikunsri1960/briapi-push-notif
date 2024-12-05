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
            'partner_service_id' => $data['partnerServiceId'],
            'customer_no' => $data['customerNo'],
            'virtual_account_no' => $data['virtualAccountNo'],
            'payment_request_id' => $data['paymentRequestId'],
            'trx_date_time' => $data['trxDateTime'],
            'additional_info' => $data['additionalInfo'],
        ]);
    }
}
