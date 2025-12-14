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

        if ($id) {
            $dados['id'] = $id;
        }

        try {
            if (isset($dados['mesa_id'])) {
                $mesaExistente = $this->mesaRepository->buscarPorId($dados['id']);

                if (!$mesaExistente) {
                    throw new \Exception("Mesa não encontrada.", 404);
                }
            } else {
                throw new \Exception('ID da mesa não fornecido.');
            }

            $pedido =  $this->repository->salvar($dados)->fresh();

            return [
                'status' => 'success',
                'message' => 'Pedido editado com sucesso',
                'data' => $pedido->toDto(),
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
