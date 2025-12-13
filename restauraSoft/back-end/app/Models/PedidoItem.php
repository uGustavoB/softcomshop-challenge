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
