<?php

namespace App\UseCases\Mesa\AtualizarStatusMesa;

class AtualizarStatusMesaUseCase implements IAtualizarStatusMesaUseCase
{
    public function execute($pedido, $status)
    {
        if ($pedido->mesa) {
            $pedido->load('mesa');

            $pedido->mesa->update(['status' => $status]);
        }
    }
}
