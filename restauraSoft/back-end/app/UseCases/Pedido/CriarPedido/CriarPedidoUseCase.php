<?php

namespace App\UseCases\Pedido\CriarPedido;

use App\Repositories\MesaRepository;
use App\Repositories\PedidoRepository;
use Illuminate\Support\Facades\Log;

class CriarPedidoUseCase implements ICriarPedidoUseCase
{
    private $repository;
    private $mesaRepository;

    /**
     * @param $repository
     */
    public function __construct(
        PedidoRepository $repository,
        MesaRepository $mesaRepository
    )
    {
        $this->repository = $repository;
        $this->mesaRepository = $mesaRepository;
    }


    public function execute(array $dados)
    {
        try {
            if (isset($dados['mesa_id'])) {
                $mesaExistente = $this->mesaRepository->buscarPorId($dados['mesa_id']);

                if (!$mesaExistente) {
                    return [
                        'status' => 'error',
                        'message' => 'Mesa não encontrada.',
                        'data' => null,
                        'http' => 404
                    ];
                }
            } else {
                throw new \Exception('ID da mesa não fornecido.');
            }

            $pedido = $this->repository->salvar($dados)->fresh();

            return [
                'status' => 'success',
                'message' => 'Pedido criado com sucesso',
                'data' => [
                    'id' => $pedido->id,
                    'mesa_id' => $pedido->mesa_id,
                    'status' => $pedido->status,
                    'tipo_pedido' => $pedido->tipo_pedido,
                    'forma_pagamento' => $pedido->forma_pagamento,
                    'valor_total' => $pedido->valor_total,
                    'observacoes' => $pedido->observacoes,
                    'data_pedido' => $pedido->data_pedido,
                ],
                'http' => 201
            ];

        } catch (Exception $e) {

            Log::error("Erro ao criar pedido: " . $e->getMessage());

            return [
                'status' => 'error',
                'message' => $e->getMessage(),
                'http' => $e->getCode() ?: 500
            ];
        }
    }
}
