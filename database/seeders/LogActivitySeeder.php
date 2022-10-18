<?php

namespace Database\Seeders;

use App\Models\LogActivity;
use Illuminate\Database\Seeder;

class LogActivitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        LogActivity::create([ 
            'id_log_activity'     => '1',
            'activity'            => 'Admin Melakukan Penambahan data (OBT-002514) Pada Halaman Data Obat',
            'tanggal'             => '2022-06-22 03:16:11'
        ]);

        LogActivity::create([ 
            'id_log_activity'     => '2',
            'activity'            => 'Admin Melakukan Penambahan data pada tanggal (2022-06-22) Pada Halaman Penjualan',
            'tanggal'             => '2022-06-21 07:54:29'
        ]);


        LogActivity::create([ 
            'id_log_activity'     => '3',
            'activity'            => 'Admin Melakukan Penghapusan data pada tanggal (2022-06-09) Pada Halaman Semua Penjualan',
            'tanggal'             => '2022-06-21 07:54:33'
        ]);

        LogActivity::create([ 
            'id_log_activity'     => '4',
            'activity'            => 'Admin Melakukan Penambahan data stok (OBT-002) Pada Halaman Stok Obat',
            'tanggal'             => '2022-06-09 02:46:16'
        ]);

        LogActivity::create([ 
            'id_log_activity'     => '5',
            'activity'            => 'Admin Melakukan Perubahan data pada tanggal (2021-11-22) Pada Halaman Penjualan',
            'tanggal'             => '2021-11-22 03:04:24'
        ]);

        LogActivity::create([ 
            'id_log_activity'     => '6',
            'activity'            => 'Admin Melakukan Penambahan data pada tanggal (2021-11-22) Pada Halaman Penjualan',
            'tanggal'             => '2022-06-09 02:46:16'
        ]);

        LogActivity::create([ 
            'id_log_activity'     => '7',
            'activity'            => 'Admin Melakukan Penambahan data pada tanggal (2021-11-22) Pada Halaman Penjualan',
            'tanggal'             => '2022-06-09 02:46:16'
        ]);

        LogActivity::create([ 
            'id_log_activity'     => '8',
            'activity'            => 'Admin Melakukan Penghapusan data (tess@gmail.com) Pada Halaman Data Pengguna',
            'tanggal'             => '2022-06-09 02:46:16'
        ]);

        LogActivity::create([ 
            'id_log_activity'     => '9',
            'activity'            => 'Admin Melakukan Penambahan data (kasir@natusi.co.id) Pada Halaman Data Pengguna',
            'tanggal'             => '2021-11-22 02:33:07'
        ]);


        LogActivity::create([ 
            'id_log_activity'     => '10',
            'activity'            => 'Admin Melakukan Penghapusan data (MS) Pada Halaman Data Supplier',
            'tanggal'             => '2021-11-22 02:32:50'
        ]);


    }
}
