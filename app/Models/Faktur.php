<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faktur extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function detail_faktur()
    {
        return $this->hasMany(DetailFaktur::class);
    }

    public function stok_barang(){
        return $this->hasMany(StokBarang::class);
    }

    public function pbf()
    {
        return $this->belongsTo(Pbf::class);
    }
    
    public function retur()
    {
        return $this->hasMany(Retur::class);
    }

}
