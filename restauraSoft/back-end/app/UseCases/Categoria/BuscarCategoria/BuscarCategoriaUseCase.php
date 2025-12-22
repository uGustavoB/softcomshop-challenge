<?php

namespace App\UseCases\Categoria\BuscarCategoria;

use App\Repositories\CategoriaRepository;
use Illuminate\Support\Facades\Log;

class BuscarCategoriaUseCase implements IBuscarCategoriaUseCase
{
    private $repository;

    public function __construct(
        CategoriaRepository $repository
    ) {
        $this->repository = $repository;
    }

    public function execute($id)
    {
        try {
            $categoria = $this->repository->buscarPorId($id);

            if (!$categoria) {
                throw new \Exception("Categoria não encontrada.", 404);
            }

            return [
                'status' => 'success',
                'data' => $categoria->toDto(),
                'http' => 200
            ];
        } catch (\Exception $e) {
            Log::error("Erro ao buscar categoria: " . $e->getMessage());

            return [
                'status' => 'error',
                'message' => $e->getMessage(),
                'http' => $e->getCode() ?: 500
            ];
        }
    }
}
