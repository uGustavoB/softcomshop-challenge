<?php

namespace App\UseCases\Pedido\CriarPedido;

use App\Repositories\MesaRepository;
use App\Repositories\PedidoRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CriarPedidoUseCase implements ICriarPedidoUseCase
{
    private $repository;
    private $mesaRepository;

    public function __construct(
        PedidoRepository $repository,
        MesaRepository $mesaRepository
    )
    {
        $this->repository = $repository;
        $this->mesaRepository = $mesaRepository;
    }


    public function execute(array $dados): array
    {
        DB::beginTransaction();

        try {
            if (isset($dados['mesa_id'])) {
                $mesaExistente = $this->mesaRepository->buscarPorId($dados['mesa_id']);

                if (!$mesaExistente) {
                    throw new \Exception("Mesa não encontrada.", 404);
                }

                if ($mesaExistente->status !== 'livre' && $mesaExistente->status !== 'ocupada' && !isset($dados['force'])) {
                    throw new \Exception("Mesa não está disponível. Status: {$mesaExistente->status}", 409);
                }
            } else {
                throw new \Exception('ID da mesa não fornecido.');
            }

            $pedido = $this->repository->salvar($dados)->fresh();

            DB::commit();

            return [
                'status' => 'success',
                'message' => 'Pedido criado com sucesso',
                'data' => $pedido->toDto(),
                'http' => 201
            ];

        } catch (Exception $e) {
            DB.rollBack();

            Log::error("Erro ao criar pedido: " . $e->getMessage());

            return [
                'status' => 'error',
                'message' => $e->getMessage(),
                'http' => $e->getCode() ?: 500
            ];
        }
    }
}
