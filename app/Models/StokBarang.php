<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StokBarang extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function detail_penjualan()
    {
        return $this->hasMany(DetailPenjualan::class);
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }

    public function pajak()
    {
        return $this->belongsTo(Pajak::class);
    }

    public function pbf()
    {
        return $this->belongsTo(Pbf::class);
    }

    public function faktur(){
        return $this->belongsTo(Faktur::class);
    }

    public function detail_retur(){
        return $this->hasMany(DetailRetur::class);
    }

    public function stok_opname(){
        return $this->hasMany(StokOpname::class);
    }
}
