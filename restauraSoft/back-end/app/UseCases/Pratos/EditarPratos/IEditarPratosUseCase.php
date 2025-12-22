<?php

namespace App\UseCases\Pratos\EditarPratos;

interface IEditarPratosUseCase
{
    public function execute($request, $id): array;
}
