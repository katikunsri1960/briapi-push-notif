<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccessToken extends Model
{
    protected $guarded = ['id'];
    
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
