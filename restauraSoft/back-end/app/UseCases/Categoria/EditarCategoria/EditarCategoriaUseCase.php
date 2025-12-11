<?php

namespace App\UseCases\Categoria\EditarCategoria;

use App\Repositories\CategoriaRepository;
use Illuminate\Support\Facades\Log;

class EditarCategoriaUseCase implements IEditarCategoriaUseCase
{
    private $repository;

    public function __construct(
        CategoriaRepository $repository
    ) {
        $this->repository = $repository;
    }

    public function execute($request, $id): array
    {
        $dados = $request->validated();

        if ($id) {
            $dados['id'] = $id;
        }

        try {
            if (isset($dados['id'])) {
                $categoriaExistente = $this->repository->buscarPorId($dados['id']);

                if (!$categoriaExistente) {
                    return [
                        'status' => 'error',
                        'message' => 'Categoria não encontrada.',
                        'data' => null,
                        'http' => 404
                    ];
                }
            } else {
                throw new \Exception('ID da categoria não fornecido.');
            }

            $categoria =  $this->repository->salvar($dados);

            return [
                'status' => 'success',
                'message' => 'Categoria editada com sucesso',
                'data' => [
                    'id' => $categoria->id,
                    'nome' => $categoria->nome,
                    'descricao' => $categoria->descricao,
                    'ordem' => $categoria->ordem,
                    'ativo' => $categoria->ativo,
                ],
                'http' => 200
            ];
        } catch (\Exception $e) {
            Log::error("Erro ao editar categoria: " . $e->getMessage());

            return [
                'status' => 'error',
                'message' => $e->getMessage(),
                'http' => $e->getCode() ?: 500
            ];
        }
    }
}
