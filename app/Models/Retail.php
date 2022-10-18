<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Retail extends Model
{
    protected $table = 'retails';
    protected $fillable = [
        'nama_barang',
        'no_bacth',
        'jumlah',
        'supplier',
        'harga_beli',
        'harga_jual',
        'tanggal_masuk',
        'tanggal_expired',
        'laba',
        'pajak',
        'status',
        'stock_awal',
        'supplier',
       
    ];
}