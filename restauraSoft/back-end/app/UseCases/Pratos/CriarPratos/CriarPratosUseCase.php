<?php

namespace App\UseCases\Pratos\CriarPratos;

use App\Repositories\PratoRepository;
use App\Services\ImagemService;
use App\UseCases\Categoria\VerificarCategoria\IVerificarCategoriaUseCase;
use Exception;
use Illuminate\Support\Facades\Log;

class CriarPratosUseCase implements ICriarPratosUseCase
{
    private $repository;
    private $verificarCategoriaUseCase;
    private $imagemService;

    public function __construct(
        PratoRepository $repository,
        IVerificarCategoriaUseCase $verificarCategoriaUseCase,
        ImagemService  $imagemService
    ) {
        $this->repository = $repository;
        $this->verificarCategoriaUseCase = $verificarCategoriaUseCase;
        $this->imagemService = $imagemService;
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

            if (isset($dados['imagem']) && $dados['imagem']) {
                $caminhoImagem = $this->imagemService->upload($dados['imagem']);
                $dados['imagem'] = $caminhoImagem;
            }

            $prato = $this->repository->salvar($dados);

            $pratoDto = $prato->toDto();
            $pratoDto['imagem_url'] = $this->imagemService->getUrl($prato->imagem);

            return [
                'status' => 'success',
                'message' => 'Prato salvo com sucesso',
                'data' => $pratoDto,
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
