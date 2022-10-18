<?php
namespace App\Exports;
use App\Models\Penjualan;
use App\Models\Barang;
use App\Models\StokBarang;
use App\Models\DetailPenjualan;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;

class PenjualanExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */

    public function collection()
    {
      return Penjualan::all();
    }

}