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
            ['nome' => 'Revelead Key'],
            ['nome' => 'Download Page'],
            ['nome' => 'Giftlink'],
            ['nome' => 'Steam Gift'],
            ['nome' => 'Free'],
            ['nome' => 'Compra Direta'],
            ['nome' => 'Troca']
        ]);
    }
}
