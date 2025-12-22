<?php

namespace App\UseCases\ItensPedido\EditarItemPedido;

interface IEditarItemPedidoUseCase
{
    public function execute($dados, $id, $pedidoId);
}
