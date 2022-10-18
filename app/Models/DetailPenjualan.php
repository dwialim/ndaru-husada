<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPenjualan extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function penjualan()
    {
        return $this->belongsTo(Penjualan::class);
    }

    public function stok_barang()
    {
        return $this->belongsTo(StokBarang::class);
    }
}
