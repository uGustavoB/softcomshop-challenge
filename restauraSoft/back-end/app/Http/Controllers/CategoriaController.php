<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoriaRequest;
use App\Repositories\CategoriaRepository;
use App\UseCases\Categoria\CriarCategorias\ICriarCategoriasUseCase;
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

    public function editar(CategoriaRequest $request, $id = null) {
        $dados = $request->all();

        if ($id) {
            $dados['id'] = $id;
        }

        $categoria = $this->repository->salvar($dados);

        return response()->json([
            'status' => 'success',
            'message' => 'Categoria salva com sucesso',
            'data' => [
                'id' => $categoria->id,
                'nome' => $categoria->nome,
                'descricao' => $categoria->descricao,
                'ordem' => $categoria->ordem,
                'ativo' => $categoria->ativo,
            ]
        ]);
    }

    public function deletar(Request $request, $id) {
        $retorno = $this->repository->deletar($id);
        return response()->json([
            'status' => $retorno ? 'success' : 'error',
            'message' => $retorno ? "Categoria deletada com sucesso" : "Erro ao deletar",
        ]);
    }
}
