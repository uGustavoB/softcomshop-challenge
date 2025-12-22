<?php

namespace App\UseCases\Pedido\ListarPedidos;

use App\Repositories\PedidoRepository;

class ListarPedidosUseCase implements IListarPedidosUseCase
{
    private $repository;

    public function __construct(
        PedidoRepository $repository
    ) {
        $this->repository = $repository;
    }

    public function execute()
    {
        $pedido = $this->repository->listagem();

        return $pedido->map(function($pedido) {
            return $pedido->toDto();
        });
    }
}
