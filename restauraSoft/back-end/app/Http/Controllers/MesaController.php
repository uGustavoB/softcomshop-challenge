<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoriaRequest;
use App\Http\Requests\MesaRequest;
use App\UseCases\Categoria\DeletarCategoria\IDeletarCategoriaUseCase;
use App\UseCases\Categoria\EditarCategoria\IEditarCategoriaUseCase;
use App\UseCases\Mesa\BuscarMesa\IBuscarMesaUseCase;
use App\UseCases\Mesa\CriarMesa\ICriarMesaUseCase;
use App\UseCases\Mesa\DeletarMesa\IDeletarMesaUseCase;
use App\UseCases\Mesa\EditarMesa\IEditarMesaUseCase;
use App\UseCases\Mesa\ListarMesa\IListarMesaUseCase;

/**
 * @OA\Tag(
 *     name="Mesa",
 *     description="Operações relacionadas a mesas do restaurante"
 * )
 */
class MesaController extends Controller
{

    /**
     * @OA\Get(
     *      path="/mesa",
     *      summary="Lista todas as mesas",
     *      tags={"Mesa"},
     *      security={{"bearerAuth": {}}},
     *      @OA\Response(
     *          response=200,
     *          description="Mesas retornadas com sucesso",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="status", type="string", example="success"),
     *              @OA\Property(property="message", type="string", example="Mesas retornadas com sucesso"),
     *              @OA\Property(
     *                  property="data",
     *                  type="array",
     *                  @OA\Items(
     *                      type="object",
     *                      @OA\Property(property="id", type="integer", example=1),
     *                      @OA\Property(property="numero", type="integer", example=1),
     *                      @OA\Property(property="capacidade", type="integer", example=4),
     *                      @OA\Property(property="descricao", type="string", example="Mesa próxima à janela"),
     *                      @OA\Property(property="status", type="string", example="disponivel", description="disponivel, ocupada, reservada, inativa"),
     *                      @OA\Property(property="ativo", type="integer", example=1, description="1 para ativo, 0 para inativo")
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
     *              @OA\Property(property="message", type="string", example="Erro ao listar mesas")
     *          )
     *      )
     * )
     */
    public function listagem(IListarMesaUseCase $useCase)
    {
        $resposta = $useCase->execute();

        return response()->json([
            'status' => 'success',
            'message' => 'Mesas retornadas com sucesso',
            'data' => $resposta
        ]);
    }

    /**
     * @OA\Get(
     *      path="/mesa/{id}",
     *      summary="Busca uma mesa específica pelo ID",
     *      tags={"Mesa"},
     *      security={{"bearerAuth": {}}},
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="ID da mesa a ser buscada",
     *          @OA\Schema(type="integer", example=1)
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Mesa retornada com sucesso",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="status", type="string", example="success"),
     *              @OA\Property(property="message", type="string", example="Mesa retornada com sucesso"),
     *              @OA\Property(
     *                  property="data",
     *                  type="object",
     *                  @OA\Property(property="id", type="integer", example=1),
     *                  @OA\Property(property="numero", type="integer", example=1),
     *                  @OA\Property(property="capacidade", type="integer", example=4),
     *                  @OA\Property(property="descricao", type="string", example="Mesa próxima à janela"),
     *                  @OA\Property(property="status", type="string", example="disponivel", description="disponivel, ocupada, reservada, inativa"),
     *                  @OA\Property(property="ativo", type="integer", example=1, description="1 para ativo, 0 para inativo")
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="ID inválido ou não fornecido",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="status", type="string", example="error"),
     *              @OA\Property(property="message", type="string", example="ID inválido ou não fornecido")
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Mesa não encontrada",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="status", type="string", example="error"),
     *              @OA\Property(property="message", type="string", example="Mesa não encontrada.")
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
     *              @OA\Property(property="message", type="string", example="Erro ao buscar mesa")
     *          )
     *      )
     * )
     */
    public function buscar(IBuscarMesaUseCase $useCase, $id)
    {
        $resposta = $useCase->execute($id);

        return response()->json([
            'status' => 'success',
            'message' => 'Mesa retornada com sucesso',
            'data' => $resposta
        ]);
    }

    /**
     * @OA\Post(
     *      path="/mesa",
     *      summary="Cria uma nova mesa",
     *      tags={"Mesa"},
     *      security={{"bearerAuth": {}}},
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"numero"},
     *              @OA\Property(property="numero", type="integer", example=5, description="Número da mesa (deve ser único)"),
     *              @OA\Property(property="capacidade", type="integer", example=6, description="Capacidade de pessoas"),
     *              @OA\Property(property="descricao", type="string", example="Mesa redonda no centro do salão", description="Descrição da mesa"),
     *              @OA\Property(property="status", type="string", example="disponivel", description="Status inicial: disponivel, ocupada, reservada, inativa"),
     *              @OA\Property(property="ativo", type="integer", example=1, description="1 para ativo, 0 para inativo")
     *          )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Mesa criada com sucesso",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="status", type="string", example="success"),
     *              @OA\Property(property="message", type="string", example="Mesa criada com sucesso"),
     *              @OA\Property(
     *                  property="data",
     *                  type="object",
     *                  @OA\Property(property="id", type="integer", example=5),
     *                  @OA\Property(property="numero", type="integer", example=5),
     *                  @OA\Property(property="capacidade", type="integer", example=6),
     *                  @OA\Property(property="descricao", type="string", example="Mesa redonda no centro do salão"),
     *                  @OA\Property(property="status", type="string", example="disponivel"),
     *                  @OA\Property(property="ativo", type="integer", example=1)
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Requisição inválida",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="error"),
     *              @OA\Property(property="message", type="string", example="Dados de entrada inválidos")
     *          )
     *      ),
     *      @OA\Response(
     *          response=409,
     *          description="Conflito - Mesa já existe",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="error"),
     *              @OA\Property(property="message", type="string", example="Já existe uma mesa com este número.")
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
     *              @OA\Property(property="message", type="string", example="Erro ao criar mesa")
     *          )
     *      )
     * )
     */
    public function cadastrar(MesaRequest $request, ICriarMesaUseCase $useCase) {
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
     *      path="/mesa/{id}",
     *      summary="Atualiza uma mesa existente",
     *      tags={"Mesa"},
     *      security={{"bearerAuth": {}}},
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="ID da mesa",
     *          @OA\Schema(type="integer", example=1)
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              @OA\Property(property="numero", type="integer", example=2, description="Número da mesa"),
     *              @OA\Property(property="capacidade", type="integer", example=8, description="Capacidade de pessoas"),
     *              @OA\Property(property="descricao", type="string", example="Mesa grande para grupos", description="Descrição da mesa"),
     *              @OA\Property(property="status", type="string", example="reservada", description="disponivel, ocupada, reservada, inativa"),
     *              @OA\Property(property="ativo", type="integer", example=1, description="1 para ativo, 0 para inativo")
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Mesa atualizada com sucesso",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="status", type="string", example="success"),
     *              @OA\Property(property="message", type="string", example="Mesa editada com sucesso"),
     *              @OA\Property(
     *                  property="data",
     *                  type="object",
     *                  @OA\Property(property="id", type="integer", example=1),
     *                  @OA\Property(property="numero", type="integer", example=2),
     *                  @OA\Property(property="capacidade", type="integer", example=8),
     *                  @OA\Property(property="descricao", type="string", example="Mesa grande para grupos"),
     *                  @OA\Property(property="status", type="string", example="reservada"),
     *                  @OA\Property(property="ativo", type="integer", example=1)
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
     *              @OA\Property(property="message", type="string", example="Erro ao editar mesa")
     *          )
     *      )
     * )
     */
    public function editar(MesaRequest $request, IEditarMesaUseCase $useCase, $id) {
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
     *      path="/mesa/{id}",
     *      summary="Exclui uma mesa",
     *      tags={"Mesa"},
     *      security={{"bearerAuth": {}}},
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="ID da mesa",
     *          @OA\Schema(type="integer", example=1)
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Mesa excluída com sucesso",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="status", type="string", example="success"),
     *              @OA\Property(property="message", type="string", example="Mesa deletada com sucesso")
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
     *          description="Mesa não encontrada",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="error"),
     *              @OA\Property(property="message", type="string", example="Mesa não encontrada.")
     *          )
     *      ),
     *      @OA\Response(
     *          response=409,
     *          description="Conflito - Existem pedidos associados",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="error"),
     *              @OA\Property(property="message", type="string", example="Não é possível deletar a mesa pois existem pedidos associados a ela.")
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
     *              @OA\Property(property="message", type="string", example="Erro ao deletar mesa")
     *          )
     *      )
     * )
     */
    public function deletar(IDeletarMesaUseCase $useCase, $id ) {
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
