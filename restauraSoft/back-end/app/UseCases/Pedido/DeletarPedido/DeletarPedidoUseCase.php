<?php

namespace App\UseCases\Pedido\DeletarPedido;

use App\Repositories\CategoriaRepository;
use App\Repositories\PedidoRepository;
use Illuminate\Support\Facades\Log;

class DeletarPedidoUseCase implements IDeletarPedidoUseCase
{
    private $repository;

    public function __construct(
        PedidoRepository $repository
    ) {
        $this->repository = $repository;
    }

    public function execute($id)
    {
        try {
            if (empty($id) && $id !== 0) {
                throw new \Exception('ID do pedido não fornecido.', 400);
            }

            if ($id == 0) {
                throw new \Exception('ID inválido.', 400);
            }

            $pedidoExistente = $this->repository->buscarPorId($id);

            if (!$pedidoExistente) {
                throw new \Exception("Pedido não encontrado.", 404);
            }

            $this->repository->deletar($id);

            return [
                'status' => 'success',
                'message' => 'Pedido deletado com sucesso',
                'data' => null,
                'http' => 200
            ];
        } catch (\Exception $e) {
            Log::error("Erro ao deletar pedido: " . $e->getMessage());

            return [
                'status' => 'error',
                'message' => $e->getMessage(),
                'http' => $e->getCode() ?: 500
            ];
        }
    }
}
