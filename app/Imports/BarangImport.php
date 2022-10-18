<?php

namespace App\Imports;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Satuan;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Illuminate\Support\Collection;

class BarangImport implements ToCollection,WithStartRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    // public function model(array $row)
    // {
    //     return new Barang([
    //         //
    //     ]);
    // }


    public function collection(Collection $rows)
    {
      // ROW 0 NAMA
      // ROW 1 Satuan
      // ROW 3 hna_ppn
      // ROW 4 hna
      // ROW 5 harga_beli
      // ROW 6 harga_jual
      // echo "<pre>";
      // print_r($rows);
      // echo "</pre>";
      // exit();

        $controller = new Controller;
        foreach ($rows as $row)
        {
            // if ($row[6] != null) {
            //     $harga_jual = $row[6];
            //     $harga_beli = $row[5];
            //     $jumlah_obat = $row[2];
            // }else{
            //     $harga_jual = '0';
            //     $harga_beli = '0';
            //     $jumlah_obat = '0';
            // }
            $master_satuan = Satuan::where('nama','like','%'.$row[2].'%')->first();
            // echo "<pre>";
            // print_r($master_satuan);
            // echo "</pre>";
            // exit();
            if (empty($master_satuan)) {
                $master_satuan = new Satuan;
                $master_satuan->nama = $row[2];
                $master_satuan->save();
            }
            $barang = new Barang;
            $kode = $controller->generateKode($barang, 'OBT');

            $barang->nama = $row[1];
            $barang->kode = $kode;
            // $barang->harga_beli = $row[3];
            $barang->satuan_id = $master_satuan->id;
            $barang->save();

            // $id =   DB::getPdo()->lastInsertId();
            // DB::table('stok_obat')->insert([
            //     'obat_id'       => $id,
            //     'harga_jual'    => $harga_jual,
            //     'harga_beli'    => $harga_beli,
            //     'jumlah_obat'   => $jumlah_obat,
            //     'hna'    => $row[4],
            //     'hna_ppn'   => $row[3],
            //     'tgl_expired'   => '2025-03-02',
            //     'tgl_datang'    => '2021-12-29',

            // ]);
        }
    }
    public function startRow(): int
    {
         return 2;
    }
}
