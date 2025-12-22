<?php

namespace App\UseCases\Mesa\EditarMesa;

interface IEditarMesaUseCase
{
    public function execute($request, $id): array;
}
