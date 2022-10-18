<?php

namespace Database\Seeders;

use App\Models\Pbf;
use Illuminate\Database\Seeder;

class PbfSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Pbf::create([
            'nama' => 'YT addaa A',
            'kode' => 'PBF-00001',
            'alamat' => 'Jl. Raya 01',
            'no_telpon' => '001122233',
            'email' => 'mail@mail.com',
            'provinsi_id' => 11,
            'kabupaten_id' => 1101,
            'kecamatan_id' => 1101010,
        ]);
        Pbf::create([
            'nama' => 'YT addaa B',
            'kode' => 'PBF-00002',
            'alamat' => 'Jl. Raya 02',
            'no_telpon' => '001122233',
            'email' => 'mail@mail.com',
            'provinsi_id' => 12,
            'kabupaten_id' => 1212,
            'kecamatan_id' => 1212260,
        ]);
    }
}
