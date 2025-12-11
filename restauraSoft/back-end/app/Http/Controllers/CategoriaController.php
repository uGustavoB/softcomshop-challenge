<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoriaRequest;
use App\Repositories\CategoriaRepository;
use App\UseCases\Categoria\CriarCategorias\ICriarCategoriasUseCase;
use App\UseCases\Categoria\DeletarCategoria\IDeletarCategoriaUseCase;
use App\UseCases\Categoria\EditarCategoria\IEditarCategoriaUseCase;
use App\UseCases\Categoria\ListarCategorias\IListarCategoriasUseCase;

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
