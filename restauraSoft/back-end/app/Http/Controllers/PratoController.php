<?php

namespace App\Http\Controllers;

use App\Http\Requests\PratoRequest;
use App\Repositories\PratoRepository;
use App\UseCases\Pratos\CriarPratos\ICriarPratosUseCase;
use App\UseCases\Pratos\DeletarPrato\IDeletarPratoUseCase;
use App\UseCases\Pratos\EditarPratos\IEditarPratosUseCase;
use App\UseCases\Pratos\ListarPratos\IListarPratosUseCase;

/**
 * @OA\Info(
 *     title="API Restaurante",
 *     version="1.0.0",
 *     description="Documentação da API Restaurante"
 * ),
 * @OA\SecurityScheme(
 *      securityScheme="bearerAuth",
 *      type="http",
 *      scheme="bearer",
 *      bearerFormat="JWT",
 *      description="Token JWT para autenticação"
 * )
 */
class PratoController extends Controller
{
    /**
     * @OA\Get(
     *      path="/prato",
     *      summary="Listagem de pratos",
     *      tags={"Prato"},
     *      security={{"bearerAuth": {}}},
     *      @OA\Response(
     *          response=200,
     *          description="Pratos retornados com sucesso",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="status", type="string", example="success"),
     *              @OA\Property(property="message", type="string", example="Pratos retornados com sucesso"),
     *              @OA\Property(
     *                  property="data",
     *                  type="array",
     *                  @OA\Items(
     *                      type="object",
     *                      @OA\Property(property="id", type="integer", example=1),
     *                      @OA\Property(property="nome", type="string", example="Prato Atualizado"),
     *                      @OA\Property(property="descricao", type="string", example="Nova descrição"),
     *                      @OA\Property(property="preco", type="number", format="float", example=30),
     *                      @OA\Property(property="imagem", type="string", example="nova_imagem.jpg"),
     *                      @OA\Property(property="categoria_id", type="integer", example=2),
     *                      @OA\Property(property="ativo", type="boolean", example=true)
     *                  )
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Erro de autenticação"
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Erro interno do servidor",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="error"),
     *              @OA\Property(property="message", type="string", example="Erro ao listar pratos")
     *          )
     *      )
     * )
     */
    public function listagem(IListarPratosUseCase $useCase)
    {
        $resposta = $useCase->execute();

        return response()->json([
            'status' => 'success',
            'message' => 'Pratos retornados com sucesso',
            'data' => $resposta
        ]);
    }

    /**
     * @OA\Post(
     *      path="/prato",
     *      summary="Cadastra um novo prato",
     *      security={{"bearerAuth": {}}},
     *      tags={"Prato"},
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"nome", "descricao", "preco", "categoria_id"},
     *              @OA\Property(property="nome", type="string", example="Prato Exemplo", description="Nome do prato (único)"),
     *              @OA\Property(property="descricao", type="string", example="Descrição do prato", description="Descrição detalhada"),
     *              @OA\Property(property="preco", type="number", format="float", example=25.5, description="Preço do prato"),
     *              @OA\Property(property="imagem", type="string", example="url_da_imagem.jpg", description="URL da imagem"),
     *              @OA\Property(property="categoria_id", type="integer", example=1, description="ID da categoria"),
     *              @OA\Property(property="ativo", type="boolean", example=true, description="Status do prato")
     *          )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Prato criado com sucesso",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="status", type="string", example="success"),
     *              @OA\Property(property="message", type="string", example="Prato salvo com sucesso"),
     *              @OA\Property(
     *                  property="data",
     *                  type="object",
     *                  @OA\Property(property="id", type="integer", example=4),
     *                  @OA\Property(property="nome", type="string", example="Prato Exemplo"),
     *                  @OA\Property(property="descricao", type="string", example="Descrição do prato"),
     *                  @OA\Property(property="preco", type="number", format="float", example=25.5),
     *                  @OA\Property(property="imagem", type="string", example="url_da_imagem.jpg"),
     *                  @OA\Property(property="categoria_id", type="integer", example=1),
     *                  @OA\Property(property="ativo", type="boolean", example=true)
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Requisição inválida",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="error"),
     *              @OA\Property(property="message", type="string", example="Categoria inválida.")
     *          )
     *      ),
     *      @OA\Response(
     *          response=409,
     *          description="Conflito - Prato já existe",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="error"),
     *              @OA\Property(property="message", type="string", example="Já existe um prato com este nome.")
     *          )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Erro de autenticação"
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Erro interno do servidor"
     *      )
     * )
     */
    public function cadastrar(PratoRequest $request, ICriarPratosUseCase $useCase) {
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
     *      path="/prato/{id}",
     *      summary="Edita um prato existente",
     *      security={{"bearerAuth": {}}},
     *      tags={"Prato"},
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="ID do prato",
     *          @OA\Schema(type="integer", example=1)
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              @OA\Property(property="nome", type="string", example="Prato Atualizado", description="Nome do prato"),
     *              @OA\Property(property="descricao", type="string", example="Nova descrição", description="Descrição atualizada"),
     *              @OA\Property(property="preco", type="number", format="float", example=30, description="Novo preço"),
     *              @OA\Property(property="imagem", type="string", example="nova_imagem.jpg", description="Nova imagem"),
     *              @OA\Property(property="categoria_id", type="integer", example=2, description="ID da categoria"),
     *              @OA\Property(property="ativo", type="boolean", example=true, description="Status do prato")
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Prato editado com sucesso",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="status", type="string", example="success"),
     *              @OA\Property(property="message", type="string", example="Prato editado com sucesso"),
     *              @OA\Property(
     *                  property="data",
     *                  type="object",
     *                  @OA\Property(property="id", type="integer", example=1),
     *                  @OA\Property(property="nome", type="string", example="Prato Atualizado"),
     *                  @OA\Property(property="descricao", type="string", example="Nova descrição"),
     *                  @OA\Property(property="preco", type="number", format="float", example=30),
     *                  @OA\Property(property="categoria_id", type="integer", example=2),
     *                  @OA\Property(property="ativo", type="boolean", example=true)
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Requisição inválida",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="error"),
     *              @OA\Property(property="message", type="string", example="Categoria inválida.")
     *          )
     *      ),
     *      @OA\Response(
     *           response=401,
     *           description="Erro de autenticação"
     *       ),
     *      @OA\Response(
     *          response=404,
     *          description="Prato não encontrado",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="error"),
     *              @OA\Property(property="message", type="string", example="Prato não encontrado.")
     *          )
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Erro interno do servidor"
     *      )
     * )
     */
    public function editar(PratoRequest $request, IEditarPratosUseCase $useCase, $id = null) {
        $resposta = $useCase->execute($request, $id);

        return response()->json([
            'status' => $resposta['status'],
            'message' => $resposta['message'],
            'data' => $resposta['data'] ?? null
        ],
            $resposta['http']
        );
    }

    /**
     * @OA\Delete(
     *      path="/prato/{id}",
     *      summary="Deleta um prato existente",
     *      security={{"bearerAuth": {}}},
     *      tags={"Prato"},
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="ID do prato",
     *          @OA\Schema(type="integer", example=1)
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Prato deletado com sucesso",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="status", type="string", example="success"),
     *              @OA\Property(property="message", type="string", example="Prato deletado com sucesso")
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
     *           response=401,
     *           description="Erro de autenticação"
     *       ),
     *      @OA\Response(
     *          response=404,
     *          description="Prato não encontrado",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="error"),
     *              @OA\Property(property="message", type="string", example="Prato não encontrado.")
     *          )
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Erro interno do servidor"
     *      )
     * )
     */
    public function deletar(IDeletarPratoUseCase $useCase, $id) {
        $resposta = $useCase->execute($id);
        return response()->json([
            'status' => $resposta['status'],
            'message' => $resposta['message'],
            'data' => $resposta['data'] ?? null
        ],
            $resposta['http']
        );
    }
}
