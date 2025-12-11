<?php

namespace App\UseCases\Pratos\CriarPratos;

interface ICriarPratosUseCase
{
    public function execute($dados): array;
}
