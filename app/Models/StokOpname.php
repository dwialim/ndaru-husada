<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StokOpname extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function stok_barang(){
        return $this->belongsTo(StokBarang::class);
    }
}
