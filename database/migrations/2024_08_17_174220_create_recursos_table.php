<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('recursos', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->decimal('preco_euro', total: 8, places: 2);
            $table->decimal('preco_dolar', total: 8, places: 2);
            $table->decimal('preco_real', total: 8, places: 2);
            $table->timestamps();
        });

        DB::table('recursos')->insert([
            ['nome' => 'TF2', 'preco_euro' => 1.37, 'preco_dolar' => 8.23, 'preco_real' => 8.24],
            ['nome' => 'Gema', 'preco_euro' => 1.37, 'preco_dolar' => 8.23, 'preco_real' => 8.24],
            ['nome' => 'ToD', 'preco_euro' => 1.37, 'preco_dolar' => 8.23, 'preco_real' => 8.24],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recursos');
    }

};
