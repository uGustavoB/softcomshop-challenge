<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoriaRequest;
use App\Repositories\CategoriaRepository;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    private $repository;

    public function __construct() {
        $this->repository = new categoriaRepository();
    }

    public function listagem()
    {
        $categorias = $this->repository->listagem();

        $categoriasFiltrados = $categorias->map(function($categoria) {
            return [
                'id' => $categoria->id,
                'nome' => $categoria->nome,
                'descricao' => $categoria->descricao,
                'ativo' => $categoria->ativo,
            ];
        });

        return response()->json([
            'status' => 'success',
            'message' => 'Categorias retornadas com sucesso',
            'data' => $categoriasFiltrados
        ]);
    }

    public function cadastrar(CategoriaRequest $request, $id = null) {
        $dados = $request->all();

        $categoria = $this->repository->salvar($dados);

        return response()->json([
            'status' => 'success',
            'message' => 'Categoria salva com sucesso',
            'data' => [
                'id' => $categoria->id,
                'nome' => $categoria->nome,
                'descricao' => $categoria->descricao,
                'ativo' => $categoria->ativo,
            ]
        ]);
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
