<?php

namespace App\Models\Connection;

use Illuminate\Database\Eloquent\Model;

class Tagihan extends Model
{
    use HasFactory;
    protected $connection = 'keu_con'; // Koneksi keuangan
    protected $table = 'tagihan';
}
