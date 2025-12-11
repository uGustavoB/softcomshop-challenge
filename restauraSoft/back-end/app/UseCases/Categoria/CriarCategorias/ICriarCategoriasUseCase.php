<?php

namespace App\UseCases\Categoria\CriarCategorias;

interface ICriarCategoriasUseCase
{
    /**
     * @param array $dados
     * @return array Categoria criada
     */
    public function execute(array $dados): array;
}
