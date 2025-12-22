<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PedidoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'mesa_id' => 'required_if:tipo_pedido,local|exists:mesas,id',
            'status' => 'in:pendente,em_preparo,pronto,entregue,cancelado,finalizado',
            'tipo_pedido' => 'required|in:local,delivery,retirada',
            'forma_pagamento' => 'nullable|in:dinheiro,cartao_credito,cartao_debito,pix,outros',
            'valor_total' => 'nullable|numeric|min:0|max:99999999.99',
            'observacoes' => 'nullable|string|max:1000',
            'data_pedido' => 'nullable|date',
        ];
    }
}
