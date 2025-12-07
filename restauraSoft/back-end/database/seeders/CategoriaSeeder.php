<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('categorias')->insert([
            [
                'nome' => 'Entradas',
                'descricao' => 'Categoria para entradas como saladas, sopas e petiscos.',
                'ordem' => 1,
            ],
            [
                'nome' => 'Pratos Principais',
                'descricao' => 'Categoria para pratos principais como carnes, massas e pratos vegetarianos.',
                'ordem' => 2,
            ],
            [
                'nome' => 'Veganos',
                'descricao' => 'Pratos totalmente veganos, sem produtos de origem animal.',
                'ordem' => 3,
            ],
            [
                'nome' => 'Sem Glúten',
                'descricao' => 'Pratos preparados sem glúten para pessoas com intolerância.',
                'ordem' => 4,
            ],
            [
                'nome' => 'Sobremesas',
                'descricao' => 'Categoria para sobremesas como bolos, sorvetes e doces.',
                'ordem' => 5,
            ],
            [
                'nome' => 'Bebidas Não Alcoólicas',
                'descricao' => 'Sucos, refrigerantes, água e chás.',
                'ordem' => 6,
            ],
            [
                'nome' => 'Bebidas Alcoólicas',
                'descricao' => 'Cervejas, vinhos, drinks e outras bebidas alcoólicas.',
                'ordem' => 7,
            ],
            [
                'nome' => 'Infantil',
                'descricao' => 'Categoria para pratos infantis.',
                'ordem' => 8,
            ]
        ]);
    }
}
