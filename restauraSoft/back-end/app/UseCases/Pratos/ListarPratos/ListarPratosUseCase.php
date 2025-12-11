<?php

namespace App\UseCases\Pratos\ListarPratos;

use App\Repositories\PratoRepository;
use Illuminate\Support\Facades\Log;

class ListarPratosUseCase implements IListarPratosUseCase
{
    private $repository;

    public function __construct(
        PratoRepository $repository
    ) {
        $this->repository = $repository;
    }

    public function execute()
    {
        try {
            $pratos = $this->repository->listagem();

            return $pratos->map(function($prato) {
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
        }catch (\Exception $e){
            Log::error("Erro ao listar pratos: " . $e->getMessage());

            return [
                'status' => 'error',
                'message' => $e->getMessage(),
                'http' => $e->getCode() ?: 500
            ];
        }
    }
}
