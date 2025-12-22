<?php

namespace App\UseCases\Categoria\VerificarCategoria;

interface IVerificarCategoriaUseCase
{
    public function execute($id): bool;
}
