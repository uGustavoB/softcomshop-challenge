<?php

namespace App\UseCases\ItensPedido\ListarItensPedido;

use App\Repositories\PedidoItemRepository;

class ListarItensPedidoUseCase implements IListarItensPedidoUseCase
{
    private $repository;

    public function __construct(
        PedidoItemRepository $repository
    ) {
        $this->repository = $repository;
    }

    public function execute($pedidoId)
    {
        $itensPedido = $this->repository->buscarPorPedidoId($pedidoId);

        return $itensPedido->map(function($item) {
            return $item->toDto();
        });
    }
}
