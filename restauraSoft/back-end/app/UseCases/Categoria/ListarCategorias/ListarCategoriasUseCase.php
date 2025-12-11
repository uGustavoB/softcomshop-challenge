<?php

namespace App\UseCases\Categoria\ListarCategorias;

use App\Repositories\CategoriaRepository;

class ListarCategoriasUseCase implements IListarCategoriasUseCase
{
    private $repository;

    public function __construct(
        CategoriaRepository $repository
    ) {
        $this->repository = $repository;
    }

    public function execute()
    {
        $categorias = $this->repository->listagem();

        return $categorias->map(function($categoria) {
            return [
                'id' => $categoria->id,
                'nome' => $categoria->nome,
                'descricao' => $categoria->descricao,
                'ordem' => $categoria->ordem,
                'ativo' => $categoria->ativo,
            ];
        });
    }
}
