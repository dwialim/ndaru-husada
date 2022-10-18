<?php

namespace App\Imports;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Satuan;
use App\Models\Pbf;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Illuminate\Support\Collection;

class PbfImport implements ToCollection,WithStartRow{
    public function collection(Collection $rows){
        $controller = new Controller;
        foreach ($rows as $row){
            $master_satuan = Satuan::where('nama','like','%'.$row[2].'%')->first();
            if (empty($master_satuan)) {
                $master_satuan = new Satuan;
                $master_satuan->nama = $row[2];
                $master_satuan->save();
            }
            $barang = new Barang;
            $kode = $controller->generateKode($barang, 'OBT');

            $barang->nama = $row[1];
            $barang->kode = $kode;
            $barang->harga_beli = $row[3];
            $barang->satuan_id = $master_satuan->id;
            $barang->save();
        }
    }
    public function startRow(): int
    {
         return 8;
    }
}
