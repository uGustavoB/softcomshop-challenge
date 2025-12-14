<?php

namespace App\UseCases\ItensPedido\EditarItemPedido;

use App\Repositories\PedidoItemRepository;
use App\Repositories\PedidoRepository;
use App\Repositories\PratoRepository;
use App\UseCases\Mesa\AtualizarStatusMesa\IAtualizarStatusMesaUseCase;
use App\UseCases\Pedido\AtualizarValorTotalPedido\IAtualizarValorTotalPedidoUseCase;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EditarItemPedidoUseCase implements IEditarItemPedidoUseCase
{
    private $repository;
    private $pedidoRepository;
    private $pratoRepository;
    private $atualizarStatusMesaUseCase;
    private $atualizarValorTotalPedidoUseCase;

    /**
     * @param $repository
     */
    public function __construct(
        PedidoItemRepository $repository,
        PedidoRepository $pedidoRepository,
        PratoRepository $pratoRepository,
        IAtualizarStatusMesaUseCase $atualizarStatusMesaUseCase,
        IAtualizarValorTotalPedidoUseCase  $atualizarValorTotalPedidoUseCase
    )
    {
        $this->repository = $repository;
        $this->pedidoRepository = $pedidoRepository;
        $this->pratoRepository = $pratoRepository;
        $this->atualizarStatusMesaUseCase = $atualizarStatusMesaUseCase;
        $this->atualizarValorTotalPedidoUseCase = $atualizarValorTotalPedidoUseCase;
    }

    public function execute($request, $id, $pedidoId)
    {
        DB::beginTransaction();

        $dados = $request->validated();

        if ($id) {
            $dados['id'] = $id;
        }

        try {
//          Validar Prato
            if (!isset($dados['prato_id'])) {
                throw new \Exception('ID do prato não fornecido.', 400);
            }

            $prato = $this->pratoRepository->buscarPorId($dados['prato_id']);

            if (!$prato) {
                throw new \Exception('Prato não encontrado.', 404);
            }

//          Validar Pedido
            if (!isset($dados['pedido_id'])) {
                throw new \Exception('ID do pedido não fornecido.', 400);
            }

            $pedido = $this->pedidoRepository->buscarPorId($dados['pedido_id']); // CORREÇÃO: mudado para pedidoRepository

            if (!$pedido) {
                throw new \Exception('Pedido não encontrado.', 404);
            }

            if (!$pedido->podeAdicionarItens()) {
                throw new \Exception('Não é possível adicionar itens a um pedido com status ' . $pedido->status, 400);
            }

            if (isset($dados['id'])) {
                $pedidoExistente = $this->repository->capturar($dados['id']);

                if (!$pedidoExistente) {
                    return [
                        'status' => 'error',
                        'message' => 'Item não encontrada.',
                        'data' => null,
                        'http' => 404
                    ];
                }
            } else {
                throw new \Exception('ID do pedido não fornecido.');
            }

            $itemPedido = $this->repository->salvar($dados)->fresh();

            // Atualiza o valor total do pedido
            $this->atualizarValorTotalPedidoUseCase->execute($pedido);


            DB::commit();

            return [
                'status' => 'success',
                'message' => 'Item de pedido atualizado com sucesso',
                'data' => [
                    'id' => $itemPedido->id,
                    'pedido_id' => $itemPedido->pedido_id,
                    'prato_id' => $itemPedido->prato_id,
                    'quantidade' => $itemPedido->quantidade,
                    'preco_unitario' => $itemPedido->preco_unitario,
                    'observacoes' => $itemPedido->observacoes,
                    'status_item' => $itemPedido->status_item,
                ],
                'http' => 200
            ];
        } catch (Exception $e) {
            DB::rollBack();

            Log::error("Erro ao criar item de pedido: " . $e->getMessage());

            return [
                'status' => 'error',
                'message' => $e->getMessage(),
                'http' => $e->getCode() ?: 500
            ];
        }
    }
}
