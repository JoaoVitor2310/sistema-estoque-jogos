<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use app\Models\Venda_chave_troca;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        
        $this->call([
            FornecedorSeeder::class,
            PlataformaSeeder::class,
            RangesTaxaG2ASeeder::class,
            RecursosSeeder::class,
            TaxasSeeder::class,
            TipoFormatoSeeder::class,
            TipoLeilaoSeeder::class,
            TipoReclamacaoSeeder::class,
            VendaChaveTrocaSeeder::class,
        ]);

        Venda_chave_troca::factory(20000)->create();
    }
}
