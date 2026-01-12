<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nama_transaksi',
        'jenis',
        'kategori',
        'total',
        'tanggal',
    ];

    protected $casts = [
        'tanggal' => 'date'
    ];
}
