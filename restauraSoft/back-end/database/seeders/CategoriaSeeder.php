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
                'nome' => 'Bebidas Alcoólicas',
                'descricao' => 'Cervejas, vinhos, drinks e outras bebidas alcoólicas.',
            ],
            [
                'nome' => 'Bebidas Não Alcoólicas',
                'descricao' => 'Sucos, refrigerantes, água e chás.',
            ],
            [
                'nome' => 'Entradas',
                'descricao' => 'Categoria para entradas como saladas, sopas e petiscos.',
            ],
            [
                'nome' => 'Pratos Principais',
                'descricao' => 'Categoria para pratos principais como carnes, massas e pratos vegetarianos.',
            ],
            [
                'nome' => 'Sobremesas',
                'descricao' => 'Categoria para sobremesas como bolos, sorvetes e doces.',
            ],
            [
                'Kids',
                'descricao' => 'Categoria para pratos infantis.',
            ],
            [
                'nome' => 'Veganos',
                'descricao' => 'Pratos totalmente veganos, sem produtos de origem animal.',
            ],
            [
                'nome' => 'Sem Glúten',
                'descricao' => 'Pratos preparados sem glúten para pessoas com intolerância.',
            ]
        ]);
    }
}
