<?php

namespace App\UseCases\ItensPedido\CriarItemPedido;

use App\Repositories\PedidoItemRepository;
use App\Repositories\PedidoRepository;
use App\Repositories\PratoRepository;
use Exception;
use Illuminate\Support\Facades\Log;

class CriarItemPedidoUseCase implements ICriarItemPedidoUseCase
{
    private $repository;
    private $pedidoRepository;
    private $pratoRepository;

    /**
     * @param $repository
     */
    public function __construct(
        PedidoItemRepository $repository,
        PedidoRepository $pedidoRepository,
        PratoRepository $pratoRepository
    )
    {
        $this->repository = $repository;
        $this->pedidoRepository = $pedidoRepository;
        $this->pratoRepository = $pratoRepository;
    }

    public function execute(array $dados)
    {
        try {
            if (isset($dados['prato_id'])) {
                $mesaExistente = $this->pratoRepository->buscarPorId($dados['prato_id']);

                if (!$mesaExistente) {
                    return [
                        'status' => 'error',
                        'message' => 'Prato não encontrada.',
                        'data' => null,
                        'http' => 404
                    ];
                }
            } else {
                throw new \Exception('ID do prato não fornecido.');
            }

            if (isset($dados['pedido_id'])) {
                $pedidoExistente = $this->pratoRepository->buscarPorId($dados['pedido_id']);

                if (!$pedidoExistente) {
                    return [
                        'status' => 'error',
                        'message' => 'Pedido não encontrada.',
                        'data' => null,
                        'http' => 404
                    ];
                }
            } else {
                throw new \Exception('ID do pedido não fornecido.');
            }

            $itemPedido = $this->repository->salvar($dados)->fresh();

//          Gustavo - Atualizar o valor total do pedido ao adicionar um novo item
//          Gustavo - Atualizar status da mesa ao adicionar item ao pedido

            return [
                'status' => 'success',
                'message' => 'Item de pedido criado com sucesso',
                'data' => [
                    'id' => $itemPedido->id,
                    'pedido_id' => $itemPedido->pedido_id,
                    'prato_id' => $itemPedido->prato_id,
                    'quantidade' => $itemPedido->quantidade,
                    'preco_unitario' => $itemPedido->preco_unitario,
                    'observacoes' => $itemPedido->observacoes,
                    'status_item' => $itemPedido->status_item,
                ],
                'http' => 201
            ];
        } catch (Exception $e) {

            Log::error("Erro ao criar item de pedido: " . $e->getMessage());

            return [
                'status' => 'error',
                'message' => $e->getMessage(),
                'http' => $e->getCode() ?: 500
            ];
        }
    }
}
