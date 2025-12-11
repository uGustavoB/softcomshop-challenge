<?php

namespace App\UseCases\Categoria\CriarCategorias;

use App\Repositories\CategoriaRepository;
use Exception;
use Illuminate\Support\Facades\Log;

class CriarCategoriasUseCase implements ICriarCategoriasUseCase
{
    private $repository;

    public function __construct(
        CategoriaRepository $repository
    ) {
        $this->repository = $repository;
    }

    public function execute(array $dados): array
    {
        try {
            if ($this->repository->existeNome($dados['nome'])) {
                throw new Exception("Já existe uma categoria com este nome.", 409);
            }

            $categoria = $this->repository->salvar($dados);

            return [
                'status' => 'success',
                'message' => 'Categoria criada com sucesso',
                'data' => [
                    'id' => $categoria->id,
                    'nome' => $categoria->nome,
                    'descricao' => $categoria->descricao,
                    'ordem' => $categoria->ordem,
                    'ativo' => $categoria->ativo,
                ],
                'http' => 201
            ];

        } catch (Exception $e) {

            Log::error("Erro ao criar categoria: " . $e->getMessage());

            return [
                'status' => 'error',
                'message' => $e->getMessage(),
                'http' => $e->getCode() ?: 500
            ];
        }
    }
}
