<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function detail_penjualan()
    {
        return $this->hasMany(DetailPenjualan::class);
    }

    public function retur()
    {
        return $this->hasMany(Retur::class);
    }
}
