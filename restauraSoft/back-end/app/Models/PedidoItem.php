<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PedidoItem extends Model
{
    protected $table = 'pedido_itens';

    protected $fillable = [
        'pedido_id',
        'prato_id',
        'quantidade',
        'preco_unitario',
        'observacoes',
        'status_item'
    ];

    protected $casts = [
        'preco_unitario' => 'decimal:2',
        'quantidade' => 'integer'
    ];

    public function toDto():array
    {
        return [
            'id' => $this->id,
            'pedido_id' => $this->pedido_id,
            'prato_id' => $this->prato_id,
            'quantidade' => $this->quantidade,
            'preco_unitario' => $this->preco_unitario,
            'observacoes' => $this->observacoes,
            'status_item' => $this->status_item,
        ];
    }

    // Relacionamentos
    public function pedido()
    {
        return $this->belongsTo(Pedido::class);
    }

    public function prato()
    {
        return $this->belongsTo(Prato::class);
    }

    // Métodos auxiliares
    public function subtotal()
    {
        return $this->quantidade * $this->preco_unitario;
    }
}
