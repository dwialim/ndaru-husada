<?php
use App\Barang;
use App\StokBarang;
use App\Models\Satuan;
namespace App\Exports;
use App\Models\Barang;
use App\Models\StokBarang;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class HampirHabisExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */

    // public function collection()
    // {
    //   return StokBarang::all();
    // }

    public function view(): View
    {
        return view('exports.laporanhbs', [
            'laporanhampirhbs' => Barang::with(['stok_barang','satuan'])->get(),
        ]);
    }
   

}