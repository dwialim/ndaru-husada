<?php

namespace Database\Seeders;

use App\Models\Barang;
use Illuminate\Database\Seeder;

class BarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Barang::create([
            'nama' => 'Lerzyn',
            'kode' => 'OBT-00001',
            'satuan_id' => 1,
        ]);
        Barang::create([
            'nama' => 'Oskadon',
            'kode' => 'OBT-00002',
            'satuan_id' => 1,
        ]);
        Barang::create([
            'nama' => 'Barang Obat',
            'kode' => 'OBT-00003',
            'satuan_id' => 2,
        ]);
    }
}
