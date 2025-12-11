<?php

namespace App\UseCases\Categoria\DeletarCategoria;

use App\Repositories\CategoriaRepository;
use Illuminate\Support\Facades\Log;

class DeletarCategoriaUseCase implements IDeletarCategoriaUseCase
{
    private $repository;

    public function __construct(
        CategoriaRepository $repository
    ) {
        $this->repository = $repository;
    }

    public function execute($id): array
    {
        try {
            if (empty($id) && $id !== 0) {
                return [
                    'status' => 'error',
                    'message' => 'ID da categoria não fornecido.',
                    'data' => null,
                    'http' => 400
                ];
            }

            if ($id == 0) {
                return [
                    'status' => 'error',
                    'message' => 'ID inválido.',
                    'data' => null,
                    'http' => 400
                ];
            }

            $categoriaExistente = $this->repository->buscarPorId($id);

            if (!$categoriaExistente) {
                return [
                    'status' => 'error',
                    'message' => 'Categoria não encontrada.',
                    'data' => null,
                    'http' => 404
                ];
            }

            $this->repository->deletar($id);

            return [
                'status' => 'success',
                'message' => 'Categoria deletada com sucesso',
                'data' => null,
                'http' => 200
            ];
        } catch (\Exception $e) {
            Log::error("Erro ao deletar categoria: " . $e->getMessage());

            return [
                'status' => 'error',
                'message' => $e->getMessage(),
                'http' => $e->getCode() ?: 500
            ];
        }
    }
}
