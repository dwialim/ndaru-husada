<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanExpired extends Model
{
    use HasFactory;
    protected $table = 'laporan_expireds';
    protected $fillable = [
        'nama_barang',       
        'satuan',        
        'jenis',        
        'expired_date',        
        'sisa_stock'         
    ];
}