<?php

namespace App\UseCases\Pratos\DeletarPrato;

use App\Repositories\PratoRepository;
use Illuminate\Support\Facades\Log;

class DeletarPratoUseCase implements IDeletarPratoUseCase
{
    private $repository;

    public function __construct(
        PratoRepository $repository
    ) {
        $this->repository = $repository;
    }

    public function execute($id)
    {
        try {
            if (empty($id) && $id !== 0) {
                return [
                    'status' => 'error',
                    'message' => 'ID do prato não fornecido.',
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

            $pratoExistente = $this->repository->buscarPorId($id);

            if (!$pratoExistente) {
                return [
                    'status' => 'error',
                    'message' => 'Prato não encontrada.',
                    'data' => null,
                    'http' => 404
                ];
            }

            $this->repository->deletar($id);

            return [
                'status' => 'success',
                'message' => 'Prato deletado com sucesso',
                'data' => null,
                'http' => 200
            ];
        } catch (\Exception $e) {
            Log::error("Erro ao deletar prato: " . $e->getMessage());

            return [
                'status' => 'error',
                'message' => $e->getMessage(),
                'http' => $e->getCode() ?: 500
            ];
        }
    }
}
