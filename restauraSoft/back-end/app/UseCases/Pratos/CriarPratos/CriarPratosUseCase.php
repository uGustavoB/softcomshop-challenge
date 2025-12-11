<?php

namespace App\UseCases\Pratos\CriarPratos;

use App\Repositories\PratoRepository;
use Exception;
use Illuminate\Support\Facades\Log;

class CriarPratosUseCase implements ICriarPratosUseCase
{
    private $repository;

    public function __construct() {
        $this->repository = new PratoRepository();
    }

    public function execute($dados): array
    {
        try {
            if ($this->repository->existeNome($dados['nome'])) {
                throw new Exception("Já existe um prato com este nome.", 409);
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
