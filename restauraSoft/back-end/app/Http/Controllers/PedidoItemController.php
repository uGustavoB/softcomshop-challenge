<?php

namespace App\Http\Controllers;

use App\Http\Requests\PedidoItemRequest;
use App\UseCases\ItensPedido\CriarItemPedido\ICriarItemPedidoUseCase;
use App\UseCases\ItensPedido\DeletarItemPedido\IDeletarItemPedidoUseCase;
use App\UseCases\ItensPedido\EditarItemPedido\IEditarItemPedidoUseCase;
use App\UseCases\ItensPedido\ListarItensPedido\IListarItensPedidoUseCase;

class PedidoItemController extends Controller
{
    public function listagem(IListarItensPedidoUseCase $useCase)
    {
        $resposta = $useCase->execute();

        return response()->json([
            'status' => 'success',
            'message' => 'Itens do pedido retornadas com sucesso',
            'data' => $resposta
        ]);
    }

    public function cadastrar(PedidoItemRequest $request, ICriarItemPedidoUseCase $useCase) {
        $dados = $request->validated();

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

    public function editar(PedidoItemRequest $request, IEditarItemPedidoUseCase $useCase, $id) {
        $resposta = $useCase->execute($request, $id);

        return response()->json(
            [
                'status' => $resposta['status'],
                'message' => $resposta['message'],
                'data' => $resposta['data'] ?? null,
            ],
            $resposta['http']
        );
    }

    public function deletar(IDeletarItemPedidoUseCase $useCase, $id ) {
        $resposta = $useCase->execute($id);

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
