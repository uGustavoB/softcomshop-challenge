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

    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'categoria_id');
    }
}
