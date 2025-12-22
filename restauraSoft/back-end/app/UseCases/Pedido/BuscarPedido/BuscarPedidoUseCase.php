<?php

namespace App\UseCases\Pedido\BuscarPedido;

use App\Repositories\PedidoRepository;
use Illuminate\Support\Facades\Log;

class BuscarPedidoUseCase implements IBuscarPedidoUseCase
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
            $pedido = $this->repository->buscarPorId($id);

            if (!$pedido) {
                throw new \Exception("Pedido não encontrado.", 404);
            }

            return $pedido->toDto();
        } catch (\Exception $e) {
            Log::error("Erro ao buscar pedido: " . $e->getMessage());

            return [
                'status' => 'error',
                'message' => $e->getMessage(),
                'http' => $e->getCode() ?: 500
            ];
        }
    }
}
