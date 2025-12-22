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
                throw new \Exception('ID da mesa não fornecido.', 400);
            }

            if ($id == 0) {
                throw new \Exception("ID inválido para deleção.", 400);
            }

            $mesaExistente = $this->repository->buscarPorId($id);

            if (!$mesaExistente) {
                throw new \Exception("Mesa não encontrada.", 404);
            }

            if ($mesaExistente->pedidos()->exists()) {
                throw new \Exception("Não é possível deletar a mesa pois existem pedidos associados a ela.", 409);
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
