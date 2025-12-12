<?php

namespace App\UseCases\Mesa\DeletarMesa;

use App\Repositories\MesaRepository;
use Illuminate\Support\Facades\Log;

class DeletarMesaUseCase implements IDeletarMesaUseCase
{
    private $repository;

    public function __construct(
        MesaRepository $repository
    ) {
        $this->repository = $repository;
    }

    public function execute($id)
    {
        try {
            if (empty($id) && $id !== 0) {
                return [
                    'status' => 'error',
                    'message' => 'ID da mesa não fornecido.',
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
                    'message' => 'Mesa não encontrada.',
                    'data' => null,
                    'http' => 404
                ];
            }

            $this->repository->deletar($id);

            return [
                'status' => 'success',
                'message' => 'Mesa deletada com sucesso',
                'data' => null,
                'http' => 200
            ];
        } catch (\Exception $e) {
            Log::error("Erro ao deletar mesa: " . $e->getMessage());

            return [
                'status' => 'error',
                'message' => $e->getMessage(),
                'http' => $e->getCode() ?: 500
            ];
        }
    }
}
