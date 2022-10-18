<?php

namespace Database\Seeders;
use App\Models\Penjualan;
use Illuminate\Database\Seeder;

class PenjualanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Penjualan::create([
            'id'                 => '1',
            'user_id'            => '1',
            'tanggal_penjualan'  =>  '2022-06-28'
        ]);
 
        Penjualan::create([
            'id'                 => '2',
            'user_id'            => '2',
            'tanggal_penjualan'  =>  '2022-06-28'
        ]);
    }
}
