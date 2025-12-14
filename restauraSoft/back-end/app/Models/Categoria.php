<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    use HasFactory;

    protected $table = 'categorias';

    // Campos preenchíveis
    protected $fillable = [
        'nome',
        'descricao',
        'ordem',
        'ativo'
    ];

    // Casts
    protected $casts = [
        'ordem' => 'integer',
        'ativo' => 'boolean',
    ];

    public function toDto(): array {
        return [
            'id' => $this->id,
            'nome' => $this->nome,
            'descricao' => $this->descricao,
            'ordem' => $this->ordem,
            'ativo' => $this->ativo
        ];
    }

    /**
     * Relacionamento com pratos
     */
    public function pratos()
    {
        return $this->hasMany(Prato::class, 'categoria_id');
    }
}
