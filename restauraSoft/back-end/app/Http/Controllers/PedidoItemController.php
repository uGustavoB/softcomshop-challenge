<?php

namespace App\Http\Controllers;

use App\Http\Requests\PedidoItemRequest;
use App\UseCases\ItensPedido\CriarItemPedido\ICriarItemPedidoUseCase;
use App\UseCases\ItensPedido\DeletarItemPedido\IDeletarItemPedidoUseCase;
use App\UseCases\ItensPedido\EditarItemPedido\IEditarItemPedidoUseCase;
use App\UseCases\ItensPedido\ListarItensPedido\IListarItensPedidoUseCase;

class PedidoItemController extends Controller
{
    public function listagem(IListarItensPedidoUseCase $useCase, $pedidoId)
    {
        $resposta = $useCase->execute($pedidoId);

        return response()->json([
            'status' => 'success',
            'message' => 'Itens do pedido retornadas com sucesso',
            'data' => $resposta
        ]);
    }

    public function cadastrar(PedidoItemRequest $request, ICriarItemPedidoUseCase $useCase, $pedidoId) {
        $dados = $request->validated();

        $dados['pedido_id'] = $pedidoId;

        $resposta = $useCase->execute($dados);

        return response()->json(
            [
                'status' => $resposta['status'],
                'message' => $resposta['message'],
                'data' => $resposta['data'] ?? null,
            ],
            $resposta['http']
        );
    }

    public function editar(PedidoItemRequest $request, IEditarItemPedidoUseCase $useCase, $pedidoId, $id) {
        $resposta = $useCase->execute($request, $id, $pedidoId);

        return response()->json(
            [
                'status' => $resposta['status'],
                'message' => $resposta['message'],
                'data' => $resposta['data'] ?? null,
            ],
            $resposta['http']
        );
    }

    public function deletar(IDeletarItemPedidoUseCase $useCase, $pedidoId, $id) {
        $resposta = $useCase->execute($id, $pedidoId);

        return response()->json(
            [
                'status' => $resposta['status'],
                'message' => $resposta['message'],
                'data' => $resposta['data'] ?? null,
            ],
            $resposta['http']
        );
    }
}
