<?php

namespace App\UseCases\Categoria\EditarCategoria;

interface IEditarCategoriaUseCase
{
    public function execute($request, $id): array;
}
