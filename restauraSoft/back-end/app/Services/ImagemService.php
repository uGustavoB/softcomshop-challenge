<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImagemService
{
    private $disk = 'public';
    private $pasta = 'pratos';

    public function upload($imagem, $nomeArquivo = null)
    {
        if (!$imagem) {
            return null;
        }

        if (!$nomeArquivo) {
            $nomeArquivo = Str::random(20) . '_' . time() . '.' . $imagem->getClientOriginalExtension();
        }

        // Remover caracteres especiais
        $nomeArquivo = preg_replace('/[^A-Za-z0-9\.\_\-]/', '', $nomeArquivo);

        $caminho = $imagem->storeAs($this->pasta, $nomeArquivo, $this->disk);

        return $caminho;
    }

    public function excluir($caminho)
    {
        if ($caminho && Storage::disk($this->disk)->exists($caminho)) {
            return Storage::disk($this->disk)->delete($caminho);
        }
        return false;
    }

    public function getUrl($caminho)
    {
        if (!$caminho) {
            return null;
        }
        return Storage::disk($this->disk)->url($caminho);
    }

    public function substituir($novaImagem, $caminhoAntigo = null)
    {
        // Exclui imagem antiga se existir
        if ($caminhoAntigo) {
            $this->excluir($caminhoAntigo);
        }

        return $this->upload($novaImagem);
    }
}
