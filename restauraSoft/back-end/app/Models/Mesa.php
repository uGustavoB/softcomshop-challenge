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
