<?php

namespace App\UseCases\ItensPedido\DeletarItemPedido;

use App\Repositories\PedidoItemRepository;
use App\Repositories\PedidoRepository;
use App\UseCases\Mesa\AtualizarStatusMesa\IAtualizarStatusMesaUseCase;
use App\UseCases\Pedido\AtualizarValorTotalPedido\IAtualizarValorTotalPedidoUseCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DeletarItemPedidoUseCase implements IDeletarItemPedidoUseCase
{
    private $repository;
    private $pedidoRepository;
    private $atualizarValorTotalPedido;
    private $atualizarStatusMesaUseCase;

    public function __construct(
        PedidoItemRepository $repository,
        PedidoRepository $pedidoRepository,
        IAtualizarValorTotalPedidoUseCase $atualizarValorTotalPedido,
        IAtualizarStatusMesaUseCase  $atualizarStatusMesa
    ) {
        $this->repository = $repository;
        $this->pedidoRepository = $pedidoRepository;
        $this->atualizarValorTotalPedido = $atualizarValorTotalPedido;
        $this->atualizarStatusMesaUseCase = $atualizarStatusMesa;
    }

    public function execute($id, $pedidoId)
    {
        try {
            DB::beginTransaction();

            if (empty($id) && $id !== 0) {
                throw new \Exception("ID do item não fornecido.", 400);
            }

            if ($id == 0) {
                throw new \Exception("ID inválido.", 400);
            }

            $itemExistente = $this->repository->capturar($id);

            if (!$itemExistente) {
                throw new \Exception("Item não encontrado.", 404);
            }

            if (!$itemExistente->relationLoaded('pedido')) {
                $itemExistente->load('pedido');
            }

            if (!$itemExistente->relationLoaded('pedido.mesa')) {
                $itemExistente->load('pedido.mesa');
            }

            $pedidoId = $itemExistente->pedido_id;
            $pedido = $itemExistente->pedido;
            $mesaId = $pedido ? $pedido->mesa_id : null;

            // Deleta o item
            $this->repository->deletar($id);

            // Atualiza o valor total do pedido
            if ($pedido) {
                $this->atualizarValorTotalPedido->execute($pedido);

                // Conta os itens restantes no pedido
                $itensRestantes = $pedido->itens()->count();

                // Atualiza status da mesa
                if ($mesaId) {
                    if ($itensRestantes > 0) {
                        $this->atualizarStatusMesaUseCase->execute($pedido, 'ocupada');
                    } else {
                        $this->atualizarStatusMesaUseCase->execute($pedido, 'livre');
                    }
                }
            }

            DB::commit();

            return [
                'status' => 'success',
                'message' => 'Item deletado com sucesso',
                'data' => null,
                'http' => 200
            ];
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error("Erro ao deletar item: " . $e->getMessage());

            return [
                'status' => 'error',
                'message' => $e->getMessage(),
                'http' => $e->getCode() ?: 500
            ];
        }
    }
}
