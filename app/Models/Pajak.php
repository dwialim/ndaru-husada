<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pajak extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function stok_barang()
    {
        return $this->hasMany(StokBarang::class);
    }

    public function detail_faktur()
    {
        return $this->hasMany(DetailFaktur::class);
    }
}
