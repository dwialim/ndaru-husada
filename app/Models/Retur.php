<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Retur extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function penjualan()
    {
        return $this->belongsTo(Penjualan::class);
    }

    public function faktur()
    {
        return $this->belongsTo(Faktur::class);
    }

    public function detail_retur()
    {
        return $this->hasMany(DetailRetur::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
