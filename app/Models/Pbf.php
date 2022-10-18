<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pbf extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function stok_barang()
    {
        return $this->hasMany(StokBarang::class);
    }

    public function faktur()
    {
        return $this->hasMany(Faktur::class);
    }

    public function provinsi()
    {
        return $this->belongsTo(Provinsi::class);
    }

    public function kabupaten()
    {
        return $this->belongsTo(Kabupaten::class);
    }

    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class);
    }

    public function desa()
    {
        return $this->belongsTo(Desa::class);
    }
}
