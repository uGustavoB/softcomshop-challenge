<?php

namespace App\UseCases\Mesa\ListarMesa;

use App\Repositories\MesaRepository;

class ListarMesaUseCase implements IListarMesaUseCase
{
    private $repository;

    public function __construct(
        MesaRepository $repository
    ) {
        $this->repository = $repository;
    }

    public function execute()
    {
        $categorias = $this->repository->listagem();

        return $categorias->map(function($categoria) {
            return [
                'id' => $categoria->id,
                'numero' => $categoria->numero,
                'capacidade' => $categoria->capacidade,
                'status' => $categoria->status,
                'localizacao' => $categoria->localizacao,
            ];
        });
    }
}
