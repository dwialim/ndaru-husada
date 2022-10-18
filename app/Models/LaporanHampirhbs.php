<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanHampirhbs extends Model
{
    use HasFactory;
    protected $table = 'laporan_hampirhbs';
    protected $fillable = [
        'nama_barang',       
        'satuan',        
        'jenis',        
        'expired_date',        
        'sisa_stock',
        'status',
    ];
}