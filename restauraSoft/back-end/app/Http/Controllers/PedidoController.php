<?php

namespace App\Http\Controllers;

use App\Http\Requests\PedidoRequest;
use App\UseCases\Pedido\BuscarPedido\IBuscarPedidoUseCase;
use App\UseCases\Pedido\CriarPedido\ICriarPedidoUseCase;
use App\UseCases\Pedido\DeletarPedido\IDeletarPedidoUseCase;
use App\UseCases\Pedido\EditarPedido\IEditarPedidoUseCase;
use App\UseCases\Pedido\ListarPedidos\IListarPedidosUseCase;

/**
 * @OA\Tag(
 *     name="Pedido",
 *     description="Operações relacionadas a pedidos do restaurante"
 * )
 */
class PedidoController extends Controller
{

    /**
     * @OA\Get(
     *      path="/pedido",
     *      summary="Lista todos os pedidos",
     *      tags={"Pedido"},
     *      security={{"bearerAuth": {}}},
     *      @OA\Response(
     *          response=200,
     *          description="Pedidos retornados com sucesso",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="status", type="string", example="success"),
     *              @OA\Property(property="message", type="string", example="Pedidos retornados com sucesso"),
     *              @OA\Property(
     *                  property="data",
     *                  type="array",
     *                  @OA\Items(
     *                      type="object",
     *                      @OA\Property(property="id", type="integer", example=1),
     *                      @OA\Property(property="mesa_id", type="integer", example=1),
     *                      @OA\Property(property="usuario_id", type="integer", example=1),
     *                      @OA\Property(property="status", type="string", example="pendente", description="pendente, em_preparo, pronto, entregue, cancelado, finalizado"),
     *                      @OA\Property(property="tipo_pedido", type="string", example="local", description="local, delivery, retirada"),
     *                      @OA\Property(property="forma_pagamento", type="string", example="dinheiro", description="dinheiro, cartao_credito, cartao_debito, pix, outros"),
     *                      @OA\Property(property="valor_total", type="number", format="float", example=125.50),
     *                      @OA\Property(property="observacoes", type="string", example="Sem cebola, por favor"),
     *                      @OA\Property(property="data_pedido", type="string", format="date-time", example="2024-01-15T14:30:00Z"),
     *                  )
     *              )
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
     *              @OA\Property(property="message", type="string", example="Erro ao listar pedidos")
     *          )
     *      )
     * )
     */
    public function listagem(IListarPedidosUseCase $useCase)
    {
        $resposta = $useCase->execute();

        return response()->json([
            'status' => 'success',
            'message' => 'Pedidos retornadas com sucesso',
            'data' => $resposta
        ]);
    }

    /**
     * @OA\Get(
     *      path="/pedido/{id}",
     *      summary="Busca um pedido específico pelo ID",
     *      tags={"Pedido"},
     *      security={{"bearerAuth": {}}},
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="ID do pedido a ser buscado",
     *          @OA\Schema(type="integer", example=1)
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Pedido retornado com sucesso",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="status", type="string", example="success"),
     *              @OA\Property(property="message", type="string", example="Pedido retornado com sucesso"),
     *              @OA\Property(
     *                  property="data",
     *                  type="object",
     *                  @OA\Property(property="id", type="integer", example=1),
     *                  @OA\Property(property="mesa_id", type="integer", example=1),
     *                  @OA\Property(property="usuario_id", type="integer", example=1),
     *                  @OA\Property(property="status", type="string", example="pendente", description="pendente, em_preparo, pronto, entregue, cancelado, finalizado"),
     *                  @OA\Property(property="tipo_pedido", type="string", example="local", description="local, delivery, retirada"),
     *                  @OA\Property(property="forma_pagamento", type="string", example="dinheiro", description="dinheiro, cartao_credito, cartao_debito, pix, outros"),
     *                  @OA\Property(property="valor_total", type="number", format="float", example=125.50),
     *                  @OA\Property(property="observacoes", type="string", example="Sem cebola, por favor"),
     *                  @OA\Property(property="data_pedido", type="string", format="date-time", example="2024-01-15T14:30:00Z")
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="ID inválido ou não fornecido",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="status", type="string", example="error"),
     *              @OA\Property(property="message", type="string", example="ID inválido ou não fornecido.")
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Pedido não encontrado",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="status", type="string", example="error"),
     *              @OA\Property(property="message", type="string", example="Pedido não encontrado.")
     *          )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Não autenticado",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="status", type="string", example="error"),
     *              @OA\Property(property="message", type="string", example="Token de autenticação inválido")
     *          )
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Erro interno do servidor",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="status", type="string", example="error"),
     *              @OA\Property(property="message", type="string", example="Erro ao buscar pedido")
     *          )
     *      )
     * )
     */
    public function buscar(IBuscarPedidoUseCase $useCase, $id)
    {
        $resposta = $useCase->execute($id);

        return response()->json([
            'status' => 'success',
            'message' => 'Pedido retornado com sucesso',
            'data' => $resposta
        ]);
    }

    /**
     * @OA\Post(
     *      path="/pedido",
     *      summary="Cria um novo pedido",
     *      tags={"Pedido"},
     *      security={{"bearerAuth": {}}},
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"mesa_id", "tipo_pedido"},
     *              @OA\Property(property="mesa_id", type="integer", example=1, description="ID da mesa (obrigatório para tipo_pedido=local)"),
     *              @OA\Property(property="status", type="string", example="pendente", description="Status inicial: pendente, em_preparo, pronto, entregue, cancelado, finalizado"),
     *              @OA\Property(property="tipo_pedido", type="string", example="local", description="Tipo: local, delivery, retirada"),
     *              @OA\Property(property="forma_pagamento", type="string", example="dinheiro", description="Forma de pagamento: dinheiro, cartao_credito, cartao_debito, pix, outros"),
     *              @OA\Property(property="valor_total", type="number", format="float", example=0, description="Valor total do pedido"),
     *              @OA\Property(property="observacoes", type="string", example="Sem cebola, por favor", description="Observações do pedido"),
     *              @OA\Property(property="data_pedido", type="string", format="date-time", example="2024-01-15T14:30:00Z", description="Data e hora do pedido (opcional)")
     *          )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Pedido criado com sucesso",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="status", type="string", example="success"),
     *              @OA\Property(property="message", type="string", example="Pedido criado com sucesso"),
     *              @OA\Property(
     *                  property="data",
     *                  type="object",
     *                  @OA\Property(property="id", type="integer", example=1),
     *                  @OA\Property(property="mesa_id", type="integer", example=1),
     *                  @OA\Property(property="usuario_id", type="integer", example=1),
     *                  @OA\Property(property="status", type="string", example="pendente"),
     *                  @OA\Property(property="tipo_pedido", type="string", example="local"),
     *                  @OA\Property(property="forma_pagamento", type="string", example="dinheiro"),
     *                  @OA\Property(property="valor_total", type="number", format="float", example=0),
     *                  @OA\Property(property="observacoes", type="string", example="Sem cebola, por favor"),
     *                  @OA\Property(property="data_pedido", type="string", format="date-time", example="2024-01-15T14:30:00Z"),
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Requisição inválida",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="error"),
     *              @OA\Property(property="message", type="string", example="ID da mesa não fornecido.")
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Mesa não encontrada",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="error"),
     *              @OA\Property(property="message", type="string", example="Mesa não encontrada.")
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
     *              @OA\Property(property="message", type="string", example="Erro ao criar pedido")
     *          )
     *      )
     * )
     */
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

    /**
     * @OA\Put(
     *      path="/pedido/{id}",
     *      summary="Atualiza um pedido existente",
     *      tags={"Pedido"},
     *      security={{"bearerAuth": {}}},
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="ID do pedido",
     *          @OA\Schema(type="integer", example=1)
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              @OA\Property(property="mesa_id", type="integer", example=2, description="ID da mesa"),
     *              @OA\Property(property="status", type="string", example="em_preparo", description="pendente, em_preparo, pronto, entregue, cancelado, finalizado"),
     *              @OA\Property(property="tipo_pedido", type="string", example="local", description="local, delivery, retirada"),
     *              @OA\Property(property="forma_pagamento", type="string", example="cartao_credito", description="dinheiro, cartao_credito, cartao_debito, pix, outros"),
     *              @OA\Property(property="valor_total", type="number", format="float", example=150.75, description="Valor total do pedido"),
     *              @OA\Property(property="observacoes", type="string", example="Adicionar mais molho", description="Observações do pedido"),
     *              @OA\Property(property="data_pedido", type="string", format="date-time", example="2024-01-15T15:00:00Z", description="Data e hora do pedido")
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Pedido atualizado com sucesso",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="status", type="string", example="success"),
     *              @OA\Property(property="message", type="string", example="Pedido editado com sucesso"),
     *              @OA\Property(
     *                  property="data",
     *                  type="object",
     *                  @OA\Property(property="id", type="integer", example=1),
     *                  @OA\Property(property="mesa_id", type="integer", example=2),
     *                  @OA\Property(property="usuario_id", type="integer", example=1),
     *                  @OA\Property(property="status", type="string", example="em_preparo"),
     *                  @OA\Property(property="tipo_pedido", type="string", example="local"),
     *                  @OA\Property(property="forma_pagamento", type="string", example="cartao_credito"),
     *                  @OA\Property(property="valor_total", type="number", format="float", example=150.75),
     *                  @OA\Property(property="observacoes", type="string", example="Adicionar mais molho"),
     *                  @OA\Property(property="data_pedido", type="string", format="date-time", example="2024-01-15T15:00:00Z"),
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Requisição inválida",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="error"),
     *              @OA\Property(property="message", type="string", example="ID do pedido não fornecido.")
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Pedido ou mesa não encontrada",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="error"),
     *              @OA\Property(property="message", type="string", example="Pedido não encontrado.")
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
     *              @OA\Property(property="message", type="string", example="Erro ao editar pedido")
     *          )
     *      )
     * )
     */
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

    /**
     * @OA\Delete(
     *      path="/pedido/{id}",
     *      summary="Exclui um pedido",
     *      tags={"Pedido"},
     *      security={{"bearerAuth": {}}},
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="ID do pedido",
     *          @OA\Schema(type="integer", example=1)
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Pedido excluído com sucesso",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="status", type="string", example="success"),
     *              @OA\Property(property="message", type="string", example="Pedido deletado com sucesso")
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="ID inválido",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="error"),
     *              @OA\Property(property="message", type="string", example="ID inválido.")
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Pedido não encontrado",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="error"),
     *              @OA\Property(property="message", type="string", example="Pedido não encontrado.")
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
     *              @OA\Property(property="message", type="string", example="Erro ao deletar pedido")
     *          )
     *      )
     * )
     */
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
