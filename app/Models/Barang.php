<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function stok_barang()
    {
        return $this->hasMany(StokBarang::class);
    }

    public function satuan()
    {
        return $this->belongsTo(Satuan::class);
    }
}
