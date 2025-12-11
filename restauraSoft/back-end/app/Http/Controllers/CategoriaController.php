<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoriaRequest;
use App\Repositories\CategoriaRepository;
use App\UseCases\Categoria\CriarCategorias\ICriarCategoriasUseCase;
use App\UseCases\Categoria\EditarCategoria\IEditarCategoriaUseCase;
use App\UseCases\Categoria\ListarCategorias\IListarCategoriasUseCase;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    private $repository;

    public function __construct(CategoriaRepository $repository)
    {
        $this->repository = $repository;
    }

    public function listagem(IListarCategoriasUseCase  $useCase)
    {
        $resposta = $useCase->execute();

        return response()->json([
            'status' => 'success',
            'message' => 'Categorias retornadas com sucesso',
            'data' => $resposta
        ]);
    }

    public function cadastrar(CategoriaRequest $request, ICriarCategoriasUseCase $createCategoriaUseCase) {
        $dados = $request->validated();

        $resultado = $createCategoriaUseCase->execute($dados);

        return response()->json(
            [
                'status' => $resultado['status'],
                'message' => $resultado['message'],
                'data' => $resultado['data'] ?? null,
            ],
            $resultado['http']
        );
    }

    public function editar(CategoriaRequest $request, IEditarCategoriaUseCase $useCase, $id = null) {
        $resultado = $useCase->execute($request, $id);

        return response()->json(
            [
                'status' => $resultado['status'],
                'message' => $resultado['message'],
                'data' => $resultado['data'] ?? null,
            ],
            $resultado['http']
        );
    }

    public function deletar(Request $request, $id) {
        $retorno = $this->repository->deletar($id);
        return response()->json([
            'status' => $retorno ? 'success' : 'error',
            'message' => $retorno ? "Categoria deletada com sucesso" : "Erro ao deletar",
        ]);
    }
}
