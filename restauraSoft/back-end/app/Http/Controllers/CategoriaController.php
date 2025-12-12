<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoriaRequest;
use App\Repositories\CategoriaRepository;
use App\UseCases\Categoria\CriarCategorias\ICriarCategoriasUseCase;
use App\UseCases\Categoria\DeletarCategoria\IDeletarCategoriaUseCase;
use App\UseCases\Categoria\EditarCategoria\IEditarCategoriaUseCase;
use App\UseCases\Categoria\ListarCategorias\IListarCategoriasUseCase;

/**
 * @OA\Tag(
 *     name="Categoria",
 *     description="Operações relacionadas a categorias de pratos"
 * )
 */
class CategoriaController extends Controller
{
    /**
     * @OA\Get(
     *      path="/categoria",
     *      summary="Lista todas as categorias",
     *      tags={"Categoria"},
     *      security={{"bearerAuth": {}}},
     *      @OA\Response(
     *          response=200,
     *          description="Categorias retornadas com sucesso",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="status", type="string", example="success"),
     *              @OA\Property(property="message", type="string", example="Categorias retornadas com sucesso"),
     *              @OA\Property(
     *                  property="data",
     *                  type="array",
     *                  @OA\Items(
     *                      type="object",
     *                      @OA\Property(property="id", type="integer", example=1),
     *                      @OA\Property(property="nome", type="string", example="Entradas"),
     *                      @OA\Property(property="descricao", type="string", example="Pratos para iniciar a refeição"),
     *                      @OA\Property(property="ordem", type="integer", example=1),
     *                      @OA\Property(property="ativo", type="boolean", example=true)
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
     *              @OA\Property(property="message", type="string", example="Erro ao listar categorias")
     *          )
     *      )
     * )
     */
    public function listagem(IListarCategoriasUseCase  $useCase)
    {
        $resposta = $useCase->execute();

        return response()->json([
            'status' => 'success',
            'message' => 'Categorias retornadas com sucesso',
            'data' => $resposta
        ]);
    }

    /**
     * @OA\Post(
     *      path="/categoria",
     *      summary="Cria uma nova categoria",
     *      tags={"Categoria"},
     *      security={{"bearerAuth": {}}},
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"nome"},
     *              @OA\Property(property="nome", type="string", example="Sobremesas", description="Nome da categoria (deve ser único)"),
     *              @OA\Property(property="descricao", type="string", example="Doces e sobremesas variadas", description="Descrição da categoria"),
     *              @OA\Property(property="ordem", type="integer", example=5, description="Ordem de exibição no menu"),
     *              @OA\Property(property="ativo", type="boolean", example=true, description="Status da categoria")
     *          )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Categoria criada com sucesso",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="status", type="string", example="success"),
     *              @OA\Property(property="message", type="string", example="Categoria criada com sucesso"),
     *              @OA\Property(
     *                  property="data",
     *                  type="object",
     *                  @OA\Property(property="id", type="integer", example=5),
     *                  @OA\Property(property="nome", type="string", example="Sobremesas"),
     *                  @OA\Property(property="descricao", type="string", example="Doces e sobremesas variadas"),
     *                  @OA\Property(property="ordem", type="integer", example=5),
     *                  @OA\Property(property="ativo", type="boolean", example=true)
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
     *          description="Conflito - Categoria já existe",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="error"),
     *              @OA\Property(property="message", type="string", example="Já existe uma categoria com este nome.")
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
     *              @OA\Property(property="message", type="string", example="Erro ao criar categoria")
     *          )
     *      )
     * )
     */
    public function cadastrar(CategoriaRequest $request, ICriarCategoriasUseCase $createCategoriaUseCase) {
        $dados = $request->validated();

        $resposta = $createCategoriaUseCase->execute($dados);

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
     *      path="/categoria/{id}",
     *      summary="Atualiza uma categoria existente",
     *      tags={"Categoria"},
     *      security={{"bearerAuth": {}}},
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="ID da categoria",
     *          @OA\Schema(type="integer", example=1)
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              @OA\Property(property="nome", type="string", example="Bebidas", description="Nome da categoria"),
     *              @OA\Property(property="descricao", type="string", example="Bebidas alcoólicas e não alcoólicas", description="Descrição da categoria"),
     *              @OA\Property(property="ordem", type="integer", example=2, description="Ordem de exibição no menu"),
     *              @OA\Property(property="ativo", type="boolean", example=true, description="Status da categoria")
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Categoria atualizada com sucesso",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="status", type="string", example="success"),
     *              @OA\Property(property="message", type="string", example="Categoria editada com sucesso"),
     *              @OA\Property(
     *                  property="data",
     *                  type="object",
     *                  @OA\Property(property="id", type="integer", example=1),
     *                  @OA\Property(property="nome", type="string", example="Bebidas"),
     *                  @OA\Property(property="descricao", type="string", example="Bebidas alcoólicas e não alcoólicas"),
     *                  @OA\Property(property="ordem", type="integer", example=2),
     *                  @OA\Property(property="ativo", type="boolean", example=true)
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Requisição inválida",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="error"),
     *              @OA\Property(property="message", type="string", example="ID da categoria não fornecido.")
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Categoria não encontrada",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="error"),
     *              @OA\Property(property="message", type="string", example="Categoria não encontrada.")
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
     *              @OA\Property(property="message", type="string", example="Erro ao editar categoria")
     *          )
     *      )
     * )
     */
    public function editar(CategoriaRequest $request, IEditarCategoriaUseCase $useCase, $id) {
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
     *      path="/categoria/{id}",
     *      summary="Exclui uma categoria",
     *      tags={"Categoria"},
     *      security={{"bearerAuth": {}}},
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="ID da categoria",
     *          @OA\Schema(type="integer", example=1)
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Categoria excluída com sucesso",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="status", type="string", example="success"),
     *              @OA\Property(property="message", type="string", example="Categoria deletada com sucesso")
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
     *          description="Categoria não encontrada",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="error"),
     *              @OA\Property(property="message", type="string", example="Categoria não encontrada.")
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
     *              @OA\Property(property="message", type="string", example="Erro ao deletar categoria")
     *          )
     *      )
     * )
     */
    public function deletar(IDeletarCategoriaUseCase $useCase, $id ) {
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
