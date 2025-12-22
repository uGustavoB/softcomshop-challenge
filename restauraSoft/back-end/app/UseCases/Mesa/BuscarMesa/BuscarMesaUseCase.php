<?php

namespace App\UseCases\Mesa\BuscarMesa;

use App\Repositories\MesaRepository;
use Illuminate\Support\Facades\Log;

class BuscarMesaUseCase implements IBuscarMesaUseCase
{
    private $repository;

    public function __construct(MesaRepository $repository) {
        $this->repository = $repository;
    }

    public function execute($id)
    {
        try {
            $mesa = $this->repository->buscarPorId($id);

            if (!$mesa) {
                throw new \Exception("Mesa não encontrada.", 404);
            }

            return [
                'status' => 'success',
                'data' => $mesa->toDto(),
                'http' => 200
            ];
        } catch (\Exception $e) {
            Log::error("Erro ao buscar mesa: " . $e->getMessage());

            return [
                'status' => 'error',
                'message' => $e->getMessage(),
                'http' => $e->getCode() ?: 500
            ];
        }
    }
}
