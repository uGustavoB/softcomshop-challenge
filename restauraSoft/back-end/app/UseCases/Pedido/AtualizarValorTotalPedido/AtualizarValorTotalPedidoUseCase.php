<?php

namespace App\UseCases\Pedido\AtualizarValorTotalPedido;

use App\Repositories\PedidoRepository;

class AtualizarValorTotalPedidoUseCase implements IAtualizarValorTotalPedidoUseCase
{
    private $repository;

    public function __construct(
        PedidoRepository $repository
    ) {
        $this->repository = $repository;
    }

    public function execute($pedido)
    {
        $pedido->load('itens');

        $valorTotal = $pedido->calcularTotal();

        $this->repository->salvar([
            'id' => $pedido->id,
            'valor_total' => $valorTotal
        ]);
    }
}
