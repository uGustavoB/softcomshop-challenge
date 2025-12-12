<?php

namespace App\UseCases\Mesa\CriarMesa;

use App\Repositories\MesaRepository;
use Exception;
use Illuminate\Support\Facades\Log;

class CriarMesaUseCase implements ICriarMesaUseCase
{
    private $repository;

    /**
     * @param $repository
     */
    public function __construct(MesaRepository $repository)
    {
        $this->repository = $repository;
    }


    public function execute(array $dados)
    {
        try {
            if ($this->repository->existeNome($dados['numero'])) {
                throw new Exception("Já existe uma mesa com este número.", 409);
            }

            $mesa = $this->repository->salvar($dados);

            return [
                'status' => 'success',
                'message' => 'Mesa criada com sucesso',
                'data' => [
                    'numero' => $mesa->numero,
                    'capacidade' => $mesa->capacidade,
                    'status' => $mesa->status,
                    'localizacao' => $mesa->localizacao,
                ],
                'http' => 201
            ];

        } catch (Exception $e) {

            Log::error("Erro ao criar mesa: " . $e->getMessage());

            return [
                'status' => 'error',
                'message' => $e->getMessage(),
                'http' => $e->getCode() ?: 500
            ];
        }
    }
}
