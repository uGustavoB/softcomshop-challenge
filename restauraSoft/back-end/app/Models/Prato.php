<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prato extends Model
{
    use HasFactory;

    protected $table = 'pratos';

    protected $fillable = [
      'nome',
      'descricao',
      'preco',
      'imagem',
      'categoria_id',
      'ativo'
    ];

    protected $casts = [
        'preco' => 'float',
        'disponivel' => 'boolean',
    ];

    public function toDto()
    {
        return [
            'id' => $this->id,
            'nome' => $this->nome,
            'descricao' => $this->descricao,
            'preco' => $this->preco,
            'imagem' => $this->imagem,
            'categoria_id' => $this->categoria_id,
            'ativo' => $this->ativo,
        ];
    }

    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'categoria_id');
    }
}
