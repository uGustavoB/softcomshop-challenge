<?php

namespace App\UseCases\Mesa\AtualizarStatusMesa;

interface IAtualizarStatusMesaUseCase
{
    public function execute($pedido, $status);
}
