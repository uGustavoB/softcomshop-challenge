<?php

namespace App\UseCases\Pratos\BuscarPrato;

use App\Repositories\PratoRepository;
use App\Services\ImagemService;
use Illuminate\Support\Facades\Log;

class BuscarPratoUseCase implements IBuscarPratoUseCase
{
    private $repository;
    private $imagemService;

    public function __construct(
        PratoRepository $repository,
        ImagemService  $imagemService
    ) {
        $this->repository = $repository;
        $this->imagemService = $imagemService;
    }

    public function execute($id)
    {
        try {
            $prato = $this->repository->buscarPorId($id);

            if (!$prato) {
                throw new \Exception("Prato não encontrado.", 404);
            }

            $pratoDto = $prato->toDto();
            $pratoDto['imagem_url'] = $this->imagemService->getUrl($prato->imagem);

            return $pratoDto;
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
