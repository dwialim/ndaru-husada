<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
            [
                'id' => 1, 
                'name' => 'Super Admin',
                'guard_name' => 'web',
            ],
            [
                'id' => 2,
                'name' => 'Kasir',
                'guard_name' => 'web',
            ],
        ]);
    }
}
