<?php

namespace App\UseCases\Mesa\EditarMesa;

use App\Repositories\CategoriaRepository;
use App\Repositories\MesaRepository;
use Illuminate\Support\Facades\Log;
use PHPUnit\Exception;

class EditarMesaUseCase implements IEditarMesaUseCase
{
    private $repository;

    public function __construct(
        MesaRepository $repository
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
                $mesaExistente = $this->repository->buscarPorId($dados['id']);

                if (!$mesaExistente) {
                    return [
                        'status' => 'error',
                        'message' => 'Mesa não encontrada.',
                        'data' => null,
                        'http' => 404
                    ];
                }
            } else {
                throw new \Exception('ID da mesa não fornecido.');
            }

            $mesa =  $this->repository->salvar($dados);

            return [
                'status' => 'success',
                'message' => 'Mesa editada com sucesso',
                'data' => [
                    'numero' => $mesa->numero,
                    'capacidade' => $mesa->capacidade,
                    'status' => $mesa->status,
                    'localizacao' => $mesa->localizacao,
                ],
                'http' => 200
            ];
        } catch (Exception $e) {
            Log::error("Erro ao editar mesa: " . $e->getMessage());

            return [
                'status' => 'error',
                'message' => $e->getMessage(),
                'http' => $e->getCode() ?: 500
            ];
        }
    }
}
