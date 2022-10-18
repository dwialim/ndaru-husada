<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SemuaPenjualan extends Model
{
    use HasFactory;
    protected $table = 'semua_penjualans';
    protected $fillable = [
        'tanggal_penjualan',
        'nama_user',
        'level_user'
    ];
}