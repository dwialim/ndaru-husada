<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LabaRugi extends Model
{
    use HasFactory;
    protected $table = 'laba_rugis';
    protected $fillable = [
        'nama_barang',       
        'satuan',        
        'jenis',        
        'expired_date',        
        'selisi_harga',  
        'qty',
        'laba_rugi'           
    ];
}