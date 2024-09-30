<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TipoReclamacaoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tipo_reclamacao')->insert([
            ['nome' => ''],
            ['nome' => 'dup'],
            ['nome' => 'rev'],
            ['nome' => 'reg']
        ]);
    }
}
