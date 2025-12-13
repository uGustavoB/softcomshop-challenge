<?php

namespace App\UseCases\ItensPedido\DeletarItemPedido;

use App\Repositories\PedidoItemRepository;
use Illuminate\Support\Facades\Log;

class DeletarItemPedidoUseCase implements IDeletarItemPedidoUseCase
{
    private $repository;

    public function __construct(
        PedidoItemRepository $repository
    ) {
        $this->repository = $repository;
    }

    public function execute($id)
    {
        try {
            if (empty($id) && $id !== 0) {
                return [
                    'status' => 'error',
                    'message' => 'ID do pedido não fornecido.',
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

            $itemExistente = $this->repository->capturar($id);

            if (!$itemExistente) {
                return [
                    'status' => 'error',
                    'message' => 'Item não encontrada.',
                    'data' => null,
                    'http' => 404
                ];
            }

            $this->repository->deletar($id);

            return [
                'status' => 'success',
                'message' => 'Item deletado com sucesso',
                'data' => null,
                'http' => 200
            ];
        } catch (\Exception $e) {
            Log::error("Erro ao deletar item: " . $e->getMessage());

            return [
                'status' => 'error',
                'message' => $e->getMessage(),
                'http' => $e->getCode() ?: 500
            ];
        }
    }
}
