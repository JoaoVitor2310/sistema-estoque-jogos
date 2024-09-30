<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TaxasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('taxas')->insert([
            ['nome' => 'gamivoPercentualMaior4', 'preco' => 0.079],
            ['nome' => 'gamivoFixoMaior4', 'preco' => 0.35],
            
            ['nome' => 'gamivoPercentualMenor4', 'preco' => 0.05],
            ['nome' => 'gamivoFixoMenor4', 'preco' => 0.1],
            
        ]);
    }
}
