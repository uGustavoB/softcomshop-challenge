<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    protected $fillable = [
        'mesa_id',
        'usuario_id',
        'status',
        'tipo_pedido',
        'forma_pagamento',
        'valor_total',
        'observacoes',
        'data_pedido'
    ];

    protected $casts = [
        'valor_total' => 'decimal:2',
        'data_pedido' => 'datetime'
    ];

    // Relacionamentos
    public function mesa()
    {
        return $this->belongsTo(Mesa::class);
    }

    public function usuario()
    {
        return $this->belongsTo(User::class);
    }

    public function itens()
    {
        return $this->hasMany(PedidoItem::class);
    }

    public function calcularTotal()
    {
        return $this->itens->sum(function ($item) {
            return $item->quantidade * $item->preco_unitario;
        });
    }

    public function podeCancelar()
    {
        return in_array($this->status, ['pendente', 'em_preparo']);
    }
}
