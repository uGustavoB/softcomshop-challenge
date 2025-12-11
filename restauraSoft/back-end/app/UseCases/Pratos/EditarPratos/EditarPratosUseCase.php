<?php

namespace App\UseCases\Pratos\EditarPratos;

use App\Repositories\PratoRepository;
use App\UseCases\Categoria\VerificarCategoria\IVerificarCategoriaUseCase;
use Illuminate\Support\Facades\Log;

class EditarPratosUseCase implements IEditarPratosUseCase
{
    private $repository;
    private $verificarCategoriaUseCase;

    public function __construct(PratoRepository $repository, IVerificarCategoriaUseCase  $verificarCategoriaUseCase) {
        $this->repository = $repository;
        $this->verificarCategoriaUseCase = $verificarCategoriaUseCase;
    }

    public function execute($request, $id): array
    {
        $dados = $request->validated();

        if ($id) {
            $dados['id'] = $id;
        }

        try {
            if (isset($dados['id'])) {
                $pratoExistente = $this->repository->buscarPorId($dados['id']);

                if (!$pratoExistente) {
                    return [
                        'status' => 'error',
                        'message' => 'Prato não encontrada.',
                        'data' => null,
                        'http' => 404
                    ];
                }
            } else {
                throw new \Exception('ID do prato não fornecido.');
            }

            if (!$this->verificarCategoriaUseCase->execute($dados['categoria_id'])) {
                throw new \Exception('Categoria inválida.');
            }

            $prato =  $this->repository->salvar($dados);

            return [
                'status' => 'success',
                'message' => 'Prato editado com sucesso',
                'data' => [
                    'id' => $prato->id,
                    'nome' => $prato->nome,
                    'descricao' => $prato->descricao,
                    'preco' => $prato->preco,
                    'categoria_id' => $prato->categoria_id,
                    'ativo' => $prato->ativo,
                ],
                'http' => 200
            ];

        } catch (\Exception $e) {
            Log::error("Erro ao editar prato: " . $e->getMessage());

            return [
                'status' => 'error',
                'message' => $e->getMessage(),
                'http' => $e->getCode() ?: 500
            ];
        }
    }
}
