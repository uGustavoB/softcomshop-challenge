<?php

namespace App\Http\Controllers;

use App\Http\Requests\PedidoItemRequest;
use App\UseCases\ItensPedido\CriarItemPedido\ICriarItemPedidoUseCase;
use App\UseCases\ItensPedido\DeletarItemPedido\IDeletarItemPedidoUseCase;
use App\UseCases\ItensPedido\EditarItemPedido\IEditarItemPedidoUseCase;
use App\UseCases\ItensPedido\ListarItensPedido\IListarItensPedidoUseCase;

/**
 * @OA\Tag(
 *     name="PedidoItem",
 *     description="Operações relacionadas aos itens de um pedido"
 * )
 */
class PedidoItemController extends Controller
{
    /**
     * @OA\Get(
     *      path="/pedido/{pedidoId}/itens",
     *      summary="Lista todos os itens de um pedido específico",
     *      tags={"PedidoItem"},
     *      security={{"bearerAuth": {}}},
     *      @OA\Parameter(
     *          name="pedidoId",
     *          in="path",
     *          required=true,
     *          description="ID do pedido",
     *          @OA\Schema(type="integer", example=1)
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Itens do pedido retornados com sucesso",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="status", type="string", example="success"),
     *              @OA\Property(property="message", type="string", example="Itens do pedido retornados com sucesso"),
     *              @OA\Property(
     *                  property="data",
     *                  type="array",
     *                  @OA\Items(
     *                      type="object",
     *                      @OA\Property(property="id", type="integer", example=1),
     *                      @OA\Property(property="pedido_id", type="integer", example=1),
     *                      @OA\Property(property="prato_id", type="integer", example=1),
     *                      @OA\Property(property="quantidade", type="integer", example=2),
     *                      @OA\Property(property="preco_unitario", type="number", format="float", example=25.50),
     *                      @OA\Property(property="observacoes", type="string", example="Sem cebola"),
     *                      @OA\Property(property="status_item", type="string", example="pendente", description="pendente, em_preparo, pronto, entregue, cancelado"),
     *                  )
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="ID do pedido inválido",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="error"),
     *              @OA\Property(property="message", type="string", example="ID do pedido não fornecido.")
     *          )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Não autenticado",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="error"),
     *              @OA\Property(property="message", type="string", example="Token de autenticação inválido")
     *          )
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Erro interno do servidor",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="error"),
     *              @OA\Property(property="message", type="string", example="Erro ao listar itens do pedido")
     *          )
     *      )
     * )
     */
    public function listagem(IListarItensPedidoUseCase $useCase, $pedidoId)
    {
        $resposta = $useCase->execute($pedidoId);

        return response()->json([
            'status' => 'success',
            'message' => 'Itens do pedido retornadas com sucesso',
            'data' => $resposta
        ]);
    }

    /**
     * @OA\Post(
     *      path="/pedido/{pedidoId}/itens",
     *      summary="Adiciona um novo item a um pedido",
     *      tags={"PedidoItem"},
     *      security={{"bearerAuth": {}}},
     *      @OA\Parameter(
     *          name="pedidoId",
     *          in="path",
     *          required=true,
     *          description="ID do pedido ao qual o item será adicionado",
     *          @OA\Schema(type="integer", example=1)
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"prato_id", "quantidade"},
     *              @OA\Property(property="prato_id", type="integer", example=1, description="ID do prato a ser adicionado"),
     *              @OA\Property(property="quantidade", type="integer", example=2, description="Quantidade do prato (mínimo: 1)"),
     *              @OA\Property(property="preco_unitario", type="number", format="float", example=25.50, description="Preço unitário do prato"),
     *              @OA\Property(property="observacoes", type="string", example="Sem cebola, por favor", description="Observações específicas para este item"),
     *              @OA\Property(property="status_item", type="string", example="pendente", description="Status do item: pendente, em_preparo, pronto, entregue, cancelado")
     *          )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Item adicionado ao pedido com sucesso",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="status", type="string", example="success"),
     *              @OA\Property(property="message", type="string", example="Item de pedido criado com sucesso"),
     *              @OA\Property(
     *                  property="data",
     *                  type="object",
     *                  @OA\Property(property="id", type="integer", example=1),
     *                  @OA\Property(property="pedido_id", type="integer", example=1),
     *                  @OA\Property(property="prato_id", type="integer", example=1),
     *                  @OA\Property(property="quantidade", type="integer", example=2),
     *                  @OA\Property(property="preco_unitario", type="number", format="float", example=25.50),
     *                  @OA\Property(property="observacoes", type="string", example="Sem cebola, por favor"),
     *                  @OA\Property(property="status_item", type="string", example="pendente"),
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Requisição inválida",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="error"),
     *              @OA\Property(property="message", type="string", example="ID do prato não fornecido.")
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Recurso não encontrado",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="error"),
     *              @OA\Property(property="message", type="string", example="Prato não encontrado.")
     *          )
     *      ),
     *      @OA\Response(
     *          response=409,
     *          description="Pedido não pode receber itens",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="error"),
     *              @OA\Property(property="message", type="string", example="Não é possível adicionar itens a um pedido com status finalizado")
     *          )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Não autenticado",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="error"),
     *              @OA\Property(property="message", type="string", example="Token de autenticação inválido")
     *          )
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Erro interno do servidor",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="error"),
     *              @OA\Property(property="message", type="string", example="Erro ao criar item de pedido")
     *          )
     *      )
     * )
     */
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

    /**
     * @OA\Put(
     *      path="/pedido/{pedidoId}/itens/{id}",
     *      summary="Atualiza um item específico de um pedido",
     *      tags={"PedidoItem"},
     *      security={{"bearerAuth": {}}},
     *      @OA\Parameter(
     *          name="pedidoId",
     *          in="path",
     *          required=true,
     *          description="ID do pedido",
     *          @OA\Schema(type="integer", example=1)
     *      ),
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="ID do item do pedido",
     *          @OA\Schema(type="integer", example=1)
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              @OA\Property(property="prato_id", type="integer", example=2, description="ID do prato (se for alterar o prato)"),
     *              @OA\Property(property="quantidade", type="integer", example=3, description="Nova quantidade"),
     *              @OA\Property(property="preco_unitario", type="number", format="float", example=28.90, description="Novo preço unitário"),
     *              @OA\Property(property="observacoes", type="string", example="Com extra de queijo", description="Novas observações"),
     *              @OA\Property(property="status_item", type="string", example="em_preparo", description="Novo status do item")
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Item atualizado com sucesso",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="status", type="string", example="success"),
     *              @OA\Property(property="message", type="string", example="Item de pedido atualizado com sucesso"),
     *              @OA\Property(
     *                  property="data",
     *                  type="object",
     *                  @OA\Property(property="id", type="integer", example=1),
     *                  @OA\Property(property="pedido_id", type="integer", example=1),
     *                  @OA\Property(property="prato_id", type="integer", example=2),
     *                  @OA\Property(property="quantidade", type="integer", example=3),
     *                  @OA\Property(property="preco_unitario", type="number", format="float", example=28.90),
     *                  @OA\Property(property="observacoes", type="string", example="Com extra de queijo"),
     *                  @OA\Property(property="status_item", type="string", example="em_preparo"),
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Requisição inválida",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="error"),
     *              @OA\Property(property="message", type="string", example="ID do prato não fornecido.")
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Recurso não encontrado",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="error"),
     *              @OA\Property(property="message", type="string", example="Item não encontrado.")
     *          )
     *      ),
     *      @OA\Response(
     *          response=409,
     *          description="Pedido não pode ser alterado",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="error"),
     *              @OA\Property(property="message", type="string", example="Não é possível adicionar itens a um pedido com status finalizado")
     *          )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Não autenticado",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="error"),
     *              @OA\Property(property="message", type="string", example="Token de autenticação inválido")
     *          )
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Erro interno do servidor",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="error"),
     *              @OA\Property(property="message", type="string", example="Erro ao atualizar item de pedido")
     *          )
     *      )
     * )
     */
    public function editar(PedidoItemRequest $request, IEditarItemPedidoUseCase $useCase, $pedidoId, $id) {
        $dados = $request->validated();

        $dados['id'] = $id;
        $dados['pedido_id'] = $pedidoId;

        $resposta = $useCase->execute($dados, $id, $pedidoId);

        return response()->json(
            [
                'status' => $resposta['status'],
                'message' => $resposta['message'],
                'data' => $resposta['data'] ?? null,
            ],
            $resposta['http']
        );
    }

    /**
     * @OA\Delete(
     *      path="/pedido/{pedidoId}/itens/{id}",
     *      summary="Remove um item específico de um pedido",
     *      tags={"PedidoItem"},
     *      security={{"bearerAuth": {}}},
     *      @OA\Parameter(
     *          name="pedidoId",
     *          in="path",
     *          required=true,
     *          description="ID do pedido",
     *          @OA\Schema(type="integer", example=1)
     *      ),
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="ID do item do pedido",
     *          @OA\Schema(type="integer", example=1)
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Item removido com sucesso",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="status", type="string", example="success"),
     *              @OA\Property(property="message", type="string", example="Item deletado com sucesso")
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="ID inválido",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="error"),
     *              @OA\Property(property="message", type="string", example="ID do item não fornecido.")
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Item não encontrado",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="error"),
     *              @OA\Property(property="message", type="string", example="Item não encontrado.")
     *          )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Não autenticado",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="error"),
     *              @OA\Property(property="message", type="string", example="Token de autenticação inválido")
     *          )
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Erro interno do servidor",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="error"),
     *              @OA\Property(property="message", type="string", example="Erro ao deletar item")
     *          )
     *      )
     * )
     */
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
