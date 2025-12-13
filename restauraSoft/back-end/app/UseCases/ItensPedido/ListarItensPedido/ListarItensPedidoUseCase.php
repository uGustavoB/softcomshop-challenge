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

    public function execute()
    {
        $itensPedido = $this->repository->listagem();

        return $itensPedido->map(function($item) {
            return [
                'id' => $item->id,
                'pedido_id' => $item->pedido_id,
                'prato_id' => $item->prato_id,
                'quantidade' => $item->quantidade,
                'preco_unitario' => $item->preco_unitario,
                'observacoes' => $item->observacoes,
                'status_item' => $item->status_item,
            ];
        });
    }
}
