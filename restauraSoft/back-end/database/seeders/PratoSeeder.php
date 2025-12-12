<?php

namespace Database\Seeders;

use App\Models\Categoria;
use App\Models\Prato;
use Illuminate\Database\Seeder;

class PratoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (Categoria::count() == 0) {
            $categorias = [
                ['nome' => 'Entradas', 'descricao' => 'Pratos para começar a refeição'],
                ['nome' => 'Pratos Principais', 'descricao' => 'Refeições principais'],
                ['nome' => 'Sobremesas', 'descricao' => 'Doces e sobremesas'],
                ['nome' => 'Bebidas', 'descricao' => 'Bebidas diversas'],
            ];

            foreach ($categorias as $categoria) {
                Categoria::create($categoria);
            }
        }

        $pratos = [
            [
                'nome' => 'Bruschetta Clássica',
                'descricao' => 'Pão italiano torrado com tomate fresco, manjericão e azeite',
                'preco' => 24.90,
                'imagem' => 'bruschetta.jpg',
                'categoria_id' => 1, // Entradas
                'ativo' => true,
            ],
            [
                'nome' => 'Filé Mignon com Batatas',
                'descricao' => 'Filé mignon grelhado ao ponto, acompanhado de batatas rústicas',
                'preco' => 59.90,
                'imagem' => 'file-mignon.jpg',
                'categoria_id' => 2, // Pratos Principais
                'ativo' => true,
            ],
            [
                'nome' => 'Risoto de Cogumelos',
                'descricao' => 'Risoto cremoso com cogumelos frescos e queijo parmesão',
                'preco' => 42.50,
                'imagem' => 'risoto-cogumelos.jpg',
                'categoria_id' => 2, // Pratos Principais
                'ativo' => true,
            ],
            [
                'nome' => 'Tiramisu Italiano',
                'descricao' => 'Clássica sobremesa italiana com café, mascarpone e cacau',
                'preco' => 29.90,
                'imagem' => 'tiramisu.jpg',
                'categoria_id' => 3, // Sobremesas
                'ativo' => true,
            ],
            [
                'nome' => 'Brownie com Sorvete',
                'descricao' => 'Brownie quente de chocolate com sorvete de creme',
                'preco' => 22.50,
                'imagem' => 'brownie-sorvete.jpg',
                'categoria_id' => 3, // Sobremesas
                'ativo' => true,
            ],
            [
                'nome' => 'Suco Natural de Laranja',
                'descricao' => 'Suco fresco de laranja espremida na hora',
                'preco' => 12.90,
                'imagem' => 'suco-laranja.jpg',
                'categoria_id' => 4, // Bebidas
                'ativo' => true,
            ],
            [
                'nome' => 'Água Mineral',
                'descricao' => 'Garrafa de 500ml com ou sem gás',
                'preco' => 6.90,
                'imagem' => null,
                'categoria_id' => 4, // Bebidas
                'ativo' => true,
            ],
            [
                'nome' => 'Salada Caesar',
                'descricao' => 'Salada com alface romana, croutons, queijo parmesão e molho caesar',
                'preco' => 32.90,
                'imagem' => 'salada-caesar.jpg',
                'categoria_id' => 1, // Entradas
                'ativo' => false, // Item inativo/indisponível
            ],
        ];

        foreach ($pratos as $pratoData) {
            Prato::create($pratoData);
        }

        $this->command->info(Prato::count() . ' pratos criados com sucesso!');
    }
}
