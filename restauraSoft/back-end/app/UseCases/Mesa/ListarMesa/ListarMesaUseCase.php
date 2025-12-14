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
        $mesas = $this->repository->listagem();

        return $mesas->map(function($mesa) {
            return $mesa->toDto();
        });
    }
}
