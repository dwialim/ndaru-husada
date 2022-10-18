<?php

namespace Database\Seeders;

use App\Models\Pajak;
use Illuminate\Database\Seeder;

class PajakSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Pajak::create([
            'nama' => 'PPn',
            'deskripsi' => 'Deskripsi ppn',
        ]);

        Pajak::create([
            'nama' => 'PPh',
            'deskripsi' => 'Deskripsi pph',
        ]);
    }
}
