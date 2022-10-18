<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PalingLaku extends Model
{
    use HasFactory;
    protected $table = 'paling_lakus';
    protected $fillable = [
        'nama_barang',       
        'satuan',        
        'jenis',        
        'qty'   
    ];
}