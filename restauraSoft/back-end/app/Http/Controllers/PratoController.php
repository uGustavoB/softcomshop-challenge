<?php

namespace App\Http\Controllers;

use App\Http\Requests\PratoRequest;
use App\Repositories\PratoRepository;
use Illuminate\Http\Request;

class PratoController extends Controller
{
    private $repository;

    public function __construct() {
        $this->repository = new PratoRepository();
    }

    public function listagem()
    {
        $pratos = $this->repository->listagem();

        $pratosFiltrados = $pratos->map(function($prato) {
            return [
                'id' => $prato->id,
                'nome' => $prato->nome,
                'descricao' => $prato->descricao,
                'preco' => $prato->preco,
                'imagem' => $prato->imagem,
                'categoria_id' => $prato->categoria_id,
                'ativo' => $prato->ativo,
            ];
        });

        return response()->json([
            'status' => 'success',
            'message' => 'Pratos retornados com sucesso',
            'data' => $pratosFiltrados
        ]);
    }

    public function salvar(PratoRequest $request, $id = null) {
        $dados = $request->all();

        if ($id) {
            $dados['id'] = $id;
        }

        $prato = $this->repository->salvar($dados);

        return response()->json([
            'status' => 'success',
            'message' => 'Prato salvo com sucesso',
            'data' => [
                'id' => $prato->id,
                'nome' => $prato->nome,
                'descricao' => $prato->descricao,
                'preco' => $prato->preco,
                'imagem' => $prato->imagem,
                'categoria_id' => $prato->categoria_id,
                'ativo' => $prato->ativo,
            ]
        ]);
    }

    public function deletar(Request $request, $id) {
        $retorno = $this->repository->deletar($id);
        return response()->json([
            'status' => $retorno ? 'success' : 'error',
            'message' => $retorno ? "Prato deletado com sucesso" : "Erro ao deletar",
        ]);
    }
}
