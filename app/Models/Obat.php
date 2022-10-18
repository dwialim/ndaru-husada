<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Obat extends Model
{
    use HasFactory;
    protected $table = 'obats';
    protected $fillable = [
        'nama_obat',       
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
        'sisa_stock'           
    ];
}