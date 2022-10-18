<?php

use Database\Seeders\PbfSeeder;
use Illuminate\Database\Seeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\UserSeeder;
use App\Models\LaporanExpired;
use App\Models\SemuaPenjualan;
use App\Models\Obat;
use App\Models\Retail;
use Database\Seeders\PajakSeeder;
use Database\Seeders\BarangSeeder;
use Database\Seeders\SatuanSeeder;
use Database\Seeders\ProvinsiSeeder;
use Database\Seeders\KabupatenSeeder;
use Database\Seeders\KecamatanSeeder;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\StokBarangSeeder;
use Database\Seeders\LogActivitySeeder;
use Database\Seeders\RolePermissionSeeder;
use Database\Seeders\MstPersentaseSeeder;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database. 
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            RoleSeeder::class,
            PermissionSeeder::class,
            UserSeeder::class,
            RolePermissionSeeder::class,
            PajakSeeder::class,
            PbfSeeder::class,
            SatuanSeeder::class,
            BarangSeeder::class,
            ProvinsiSeeder::class,
            KabupatenSeeder::class,
            KecamatanSeeder::class,
            StokBarangSeeder::class,
            LogActivitySeeder::class,
            MstPersentaseSeeder::class,
        ]);
    }
}