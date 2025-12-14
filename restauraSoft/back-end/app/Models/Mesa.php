<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mesa extends Model
{
    protected $fillable = [
        'numero',
        'capacidade',
        'status',
        'localizacao',
    ];

    protected $casts = [
        'capacidade' => 'integer'
    ];

    public function toDto(): array
    {
        return [
            'id' => $this->id,
            'numero' => $this->numero,
            'capacidade' => $this->capacidade,
            'status' => $this->status,
            'localizacao' => $this->localizacao
        ];
    }

    public function pedidos()
    {
        return $this->hasMany(Pedido::class);
    }

    public function pedidosAtivos()
    {
        return $this->pedidos()->whereIn('status', ['pendente', 'em_preparo', 'pronto']);
    }

    public function scopeLivres($query)
    {
        return $query->where('status', 'livre');
    }

    public function scopeOcupadas($query)
    {
        return $query->where('status', 'ocupada');
    }
}
