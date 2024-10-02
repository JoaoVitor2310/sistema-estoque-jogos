<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TipoFormatoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tipo_formato')->insert([
            ['name' => 'Revelead Key'],
            ['name' => 'Download Page'],
            ['name' => 'Giftlink'],
            ['name' => 'Steam Gift'],
            ['name' => 'Free'],
            ['name' => 'Compra Direta'],
            ['name' => 'Troca']
        ]);
    }
}
