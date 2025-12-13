<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoriaRequest;
use App\Http\Requests\PedidoRequest;
use App\UseCases\Categoria\DeletarCategoria\IDeletarCategoriaUseCase;
use App\UseCases\Categoria\EditarCategoria\IEditarCategoriaUseCase;
use App\UseCases\Pedido\CriarPedido\ICriarPedidoUseCase;
use App\UseCases\Pedido\DeletarPedido\IDeletarPedidoUseCase;
use App\UseCases\Pedido\EditarPedido\IEditarPedidoUseCase;
use App\UseCases\Pedido\ListarPedidos\IListarPedidosUseCase;

class PedidoController extends Controller
{
    public function listagem(IListarPedidosUseCase $useCase)
    {
        $resposta = $useCase->execute();

        return response()->json([
            'status' => 'success',
            'message' => 'Pedidos retornadas com sucesso',
            'data' => $resposta
        ]);
    }

    public function cadastrar(PedidoRequest $request, ICriarPedidoUseCase $useCase) {
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

    public function editar(PedidoRequest $request, IEditarPedidoUseCase $useCase, $id) {
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

    public function deletar(IDeletarPedidoUseCase $useCase, $id ) {
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
