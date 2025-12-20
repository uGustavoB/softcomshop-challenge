<?php

namespace App\UseCases\Pedido\DeletarPedido;

use App\Repositories\CategoriaRepository;
use App\Repositories\MesaRepository;
use App\Repositories\PedidoItemRepository;
use App\Repositories\PedidoRepository;
use Illuminate\Support\Facades\Log;

class DeletarPedidoUseCase implements IDeletarPedidoUseCase
{
    private $repository;
    private $mesaRepository;

    public function __construct(
        PedidoRepository $repository,
        MesaRepository $mesaRepository
    ) {
        $this->repository = $repository;
        $this->mesaRepository = $mesaRepository;
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

            $mesa = $this->mesaRepository->buscarPorId($pedidoExistente->mesa_id);
            if ($mesa->status === 'ocupada') {;
                $mesa->status = 'livre';
                $mesa->save();
            }

            $mesaTemPedidos = $this->repository->verificarPedidosNaMesa($pedidoExistente->mesa_id);
            if (!$mesaTemPedidos) {
                $mesa->status = 'livre';
                $mesa->save();
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
