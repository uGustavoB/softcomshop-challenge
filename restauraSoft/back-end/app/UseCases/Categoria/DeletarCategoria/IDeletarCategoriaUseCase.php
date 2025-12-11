<?php

namespace App\UseCases\Categoria\DeletarCategoria;

interface IDeletarCategoriaUseCase
{
    public function execute($id): array;
}
