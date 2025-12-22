<?php

namespace App\UseCases\Pratos\EditarPratos;

use App\Repositories\PratoRepository;
use App\Services\ImagemService;
use App\UseCases\Categoria\VerificarCategoria\IVerificarCategoriaUseCase;
use Illuminate\Support\Facades\Log;

class EditarPratosUseCase implements IEditarPratosUseCase
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

    public function execute($request, $id): array
    {
        $dados = $request->validated();

        if ($id) {
            $dados['id'] = $id;
        }

        try {
            if (isset($dados['id'])) {
                $pratoExistente = $this->repository->buscarPorId($dados['id']);

                if (!$pratoExistente) {
                    return [
                        'status' => 'error',
                        'message' => 'Prato não encontrado.',
                        'data' => null,
                        'http' => 404
                    ];
                }
            } else {
                throw new \Exception('ID do prato não fornecido.');
            }

            if (!$this->verificarCategoriaUseCase->execute($dados['categoria_id'])) {
                throw new \Exception('Categoria inválida.');
            }

            if ($request->hasFile('imagem')) {
                // Se tem nova imagem, faz upload
                $caminhoImagem = $this->imagemService->substituir(
                    $request->file('imagem'),
                    $pratoExistente->imagem
                );
                $dados['imagem'] = $caminhoImagem;
            } else if (isset($dados['imagem_existente'])) {
                $dados['imagem'] = $dados['imagem_existente'];
                unset($dados['imagem_existente']);
            } else {
                unset($dados['imagem']);
            }

            $prato =  $this->repository->salvar($dados);

            $pratoDto = $prato->toDto();
            $pratoDto['imagem_url'] = $this->imagemService->getUrl($prato->imagem);

            return [
                'status' => 'success',
                'message' => 'Prato editado com sucesso',
                'data' => $pratoDto,
                'http' => 200
            ];

        } catch (\Exception $e) {
            Log::error("Erro ao editar prato: " . $e->getMessage());

            return [
                'status' => 'error',
                'message' => $e->getMessage(),
                'http' => $e->getCode() ?: 500
            ];
        }
    }
}
