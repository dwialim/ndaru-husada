<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailRetur extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function retur()
    {
        return $this->belongsTo(Retur::class);
    }

    public function stok_barang()
    {
        return $this->belongsTo(StokBarang::class);
    }

    public function detail_penjualan()
    {
        return $this->belongsTo(DetailPenjualan::class);
    }

}
