<?php

namespace App\UseCases\Categoria\VerificarCategoria;

use App\Repositories\CategoriaRepository;

class VerificarCategoriaUseCase implements IVerificarCategoriaUseCase
{
    private $repository;

    public function __construct(
        CategoriaRepository $repository
    ) {
        $this->repository = $repository;
    }

    public function execute($id): bool
    {
        if ($this->repository->buscarPorId($id)) {
            return true;
        }
        return false;
    }
}
