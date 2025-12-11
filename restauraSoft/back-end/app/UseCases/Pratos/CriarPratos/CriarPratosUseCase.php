<?php

namespace App\UseCases\Pratos\CriarPratos;

use App\Repositories\PratoRepository;
use App\UseCases\Categoria\VerificarCategoria\IVerificarCategoriaUseCase;
use Exception;
use Illuminate\Support\Facades\Log;

class CriarPratosUseCase implements ICriarPratosUseCase
{
    private $repository;
    private $verificarCategoriaUseCase;

    public function __construct(
        PratoRepository $repository,
        IVerificarCategoriaUseCase $verificarCategoriaUseCase
    ) {
        $this->repository = $repository;
        $this->verificarCategoriaUseCase = $verificarCategoriaUseCase;
    }

    public function execute($dados): array
    {
        try {
            if ($this->repository->existeNome($dados['nome'])) {
                throw new Exception("Já existe um prato com este nome.", 409);
            }

            if (!$this->verificarCategoriaUseCase->execute($dados['categoria_id'])) {
                throw new Exception("Categoria inválida.", 400);
            }

            $prato = $this->repository->salvar($dados);

            return [
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
                ],
                'http' => 201
            ];
        } catch (Exception $e) {
            Log::error("Erro ao criar prato: " . $e->getMessage());

            return [
                'status' => 'error',
                'message' => $e->getMessage(),
                'http' => $e->getCode() ?: 500
            ];
        }
    }
}
