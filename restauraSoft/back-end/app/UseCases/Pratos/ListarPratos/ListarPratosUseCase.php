<?php

namespace App\UseCases\Pratos\ListarPratos;

use App\Repositories\PratoRepository;
use App\Services\ImagemService;
use Illuminate\Support\Facades\Log;

class ListarPratosUseCase implements IListarPratosUseCase
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

    public function execute()
    {
        try {
            $pratos = $this->repository->listagem();

            return $pratos->map(function($prato) {
                $dto = $prato->toDto();
                $dto['imagem_url'] = $this->imagemService->getUrl($prato->imagem);
                return $dto;
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
