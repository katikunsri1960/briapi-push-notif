<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccessToken extends Model
{
    protected $guarded = ['id'];

    public function bank()
    {
        return $this->belongsTo(BankInfo::class);
    }
}
