<?php

namespace App\UseCases\Pedido\EditarPedido;

use App\Http\Requests\PedidoRequest;
use App\Repositories\MesaRepository;
use App\Repositories\PedidoRepository;
use Illuminate\Support\Facades\Log;
use PHPUnit\Exception;

class EditarPedidoUseCase implements IEditarPedidoUseCase
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

    public function execute($request, $id)
    {
        $dados = $request->validated();

        try {
            $pedidoExistente = $this->repository->buscarPorId($id);

            if (!$pedidoExistente) {
                throw new \Exception('Pedido não encontrado.', 404);
            }

            if (isset($dados['mesa_id'])) {
                $mesaExistente = $this->mesaRepository->buscarPorId($dados['mesa_id']);

                if (!$mesaExistente) {
                    throw new \Exception('Mesa não encontrada.', 404);
                }
            }

            $pedidoExistente->fill($dados);
            $pedidoExistente->save();

            return [
                'status' => 'success',
                'message' => 'Pedido editado com sucesso',
                'data' => $pedidoExistente->toDto(),
                'http' => 200
            ];
        } catch (Exception $e) {
            Log::error("Erro ao editar pedido: " . $e->getMessage());

            return [
                'status' => 'error',
                'message' => $e->getMessage(),
                'http' => $e->getCode() ?: 500
            ];
        }
    }
}
