<?php

namespace App\Http\Controllers;

use App\Http\Requests\PratoRequest;
use App\Repositories\PratoRepository;
use App\UseCases\Pratos\CriarPratos\ICriarPratosUseCase;
use App\UseCases\Pratos\EditarPratos\IEditarPratosUseCase;
use App\UseCases\Pratos\ListarPratos\IListarPratosUseCase;
use Illuminate\Http\Request;

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
    private $repository;

    public function __construct() {
        $this->repository = new PratoRepository();
    }

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
     *                      @OA\Property(property="ativo", type="integer", example=1)
     *                  )
     *              ),
     *              @OA\Examples(
     *                  example="pratos",
     *                  value={
     *                      "status": "success",
     *                      "message": "Pratos retornados com sucesso",
     *                      "data": {
     *                          {"id": 1, "nome": "Prato Atualizado", "descricao": "Nova descrição", "preco": 30, "imagem": "nova_imagem.jpg", "categoria_id": 2, "ativo": 1},
     *                          {"id": 4, "nome": "Prato Exemplo", "descricao": "Descrição do prato", "preco": 25.5, "imagem": "url_da_imagem.jpg", "categoria_id": 1, "ativo": 1}
     *                      }
     *                  },
     *                  summary="Exemplo de retorno da listagem de pratos"
     *              )
     *          )
     *      ),
     *     @OA\Response(
     *           response=401,
     *           description="Erro de autenticação"
     *       )
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
     *              type="object",
     *              @OA\Property(property="nome", type="string", example="Prato Exemplo"),
     *              @OA\Property(property="descricao", type="string", example="Descrição do prato"),
     *              @OA\Property(property="preco", type="number", format="float", example=25.5),
     *              @OA\Property(property="imagem", type="string", example="url_da_imagem.jpg"),
     *              @OA\Property(property="categoria_id", type="integer", example=1),
     *              @OA\Property(property="ativo", type="boolean", example=true)
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Prato salvo com sucesso",
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
     *              ),
     *              @OA\Examples(
     *                  example="prato",
     *                  value={
     *                      "status": "success",
     *                      "message": "Prato salvo com sucesso",
     *                      "data": {
     *                          "id": 4,
     *                          "nome": "Prato Exemplo",
     *                          "descricao": "Descrição do prato",
     *                          "preco": 25.5,
     *                          "imagem": "url_da_imagem.jpg",
     *                          "categoria_id": 1,
     *                          "ativo": true
     *                      }
     *                  },
     *                  summary="Exemplo de retorno ao cadastrar um prato"
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Erro de autenticação"
     *      )
     * )
     */
    public function cadastrar(PratoRequest $request, ICriarPratosUseCase $useCase, $id = null) {
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
     *              type="object",
     *              @OA\Property(property="nome", type="string", example="Prato Atualizado"),
     *              @OA\Property(property="descricao", type="string", example="Nova descrição"),
     *              @OA\Property(property="preco", type="number", format="float", example=30),
     *              @OA\Property(property="imagem", type="string", example="nova_imagem.jpg"),
     *              @OA\Property(property="categoria_id", type="integer", example=2),
     *              @OA\Property(property="ativo", type="boolean", example=true)
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Prato editado com sucesso",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="status", type="string", example="success"),
     *              @OA\Property(property="message", type="string", example="Prato salvo com sucesso"),
     *              @OA\Property(
     *                  property="data",
     *                  type="object",
     *                  @OA\Property(property="id", type="integer", example=1),
     *                  @OA\Property(property="nome", type="string", example="Prato Atualizado"),
     *                  @OA\Property(property="descricao", type="string", example="Nova descrição"),
     *                  @OA\Property(property="preco", type="number", format="float", example=30),
     *                  @OA\Property(property="imagem", type="string", example="nova_imagem.jpg"),
     *                  @OA\Property(property="categoria_id", type="integer", example=2),
     *                  @OA\Property(property="ativo", type="boolean", example=true)
     *              ),
     *              @OA\Examples(
     *                  example="prato_editado",
     *                  value={
     *                      "status": "success",
     *                      "message": "Prato salvo com sucesso",
     *                      "data": {
     *                          "id": 1,
     *                          "nome": "Prato Atualizado",
     *                          "descricao": "Nova descrição",
     *                          "preco": 30,
     *                          "imagem": "nova_imagem.jpg",
     *                          "categoria_id": 2,
     *                          "ativo": true
     *                      }
     *                  },
     *                  summary="Exemplo de retorno ao editar um prato"
     *              )
     *          )
     *      ),
     *     @OA\Response(
     *           response=401,
     *           description="Erro de autenticação"
     *       )
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
     *              @OA\Property(property="message", type="string", example="Prato deletado com sucesso"),
     *              @OA\Examples(
     *                  example="prato_deletado",
     *                  value={
     *                      "status": "success",
     *                      "message": "Prato deletado com sucesso"
     *                  },
     *                  summary="Exemplo de retorno ao deletar um prato"
     *              )
     *          )
     *      ),
     *     @OA\Response(
     *           response=401,
     *           description="Erro de autenticação"
     *      )
     * )
     */
    public function deletar(Request $request, $id) {
        $retorno = $this->repository->deletar($id);
        return response()->json([
            'status' => $retorno ? 'success' : 'error',
            'message' => $retorno ? "Prato deletado com sucesso" : "Erro ao deletar",
        ]);
    }
}
