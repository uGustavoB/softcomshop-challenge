<?php

namespace App\UseCases\Pedido\ListarPedidos;

use App\Repositories\CategoriaRepository;
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
            return [
                'id' => $pedido->id,
                'mesa_id' => $pedido->mesa_id,
                'status' => $pedido->status,
                'tipo_pedido' => $pedido->tipo_pedido,
                'forma_pagamento' => $pedido->forma_pagamento,
                'valor_total' => $pedido->valor_total,
                'observacoes' => $pedido->observacoes,
                'data_pedido' => $pedido->data_pedido,
            ];
        });
    }
}
