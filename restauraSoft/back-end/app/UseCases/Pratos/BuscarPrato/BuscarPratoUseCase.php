<?php

namespace App\UseCases\Pratos\BuscarPrato;

use App\Repositories\PratoRepository;
use Illuminate\Support\Facades\Log;

class BuscarPratoUseCase implements IBuscarPratoUseCase
{
    private $repository;

    public function __construct(
        PratoRepository $repository
    ) {
        $this->repository = $repository;
    }

    public function execute($id)
    {
        try {
            $prato = $this->repository->buscarPorId($id);

            if (!$prato) {
                throw new \Exception("Prato não encontrado.", 404);
            }

            return $prato->toDto();
        } catch (\Exception $e) {
            Log::error("Erro ao buscar prato: " . $e->getMessage());

            return [
                'status' => 'error',
                'message' => $e->getMessage(),
                'http' => $e->getCode() ?: 500
            ];
        }
    }
}
