<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlataformaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('plataforma')->insert([
            ['nome' => ''],
            ['nome' => 'G2A'],
            ['nome' => 'Gamivo'],
            ['nome' => 'Kinguin'],
            ['nome' => 'Eneba'],
        ]);
    }
}
