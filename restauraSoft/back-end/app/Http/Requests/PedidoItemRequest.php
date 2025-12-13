<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PedidoItemRequest extends FormRequest
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
            "pedido_id" => "required|integer|exists:pedidos,id",
            "prato_id" => "required|integer|exists:pratos,id",
            "quantidade" => "required|integer|min:1",
            "preco_unitario" => "required|numeric|min:0",
            "observacoes" => "nullable|string",
            "status_item" => "nullable|in:pendente,em_preparo,pronto,entregue,cancelado",
        ];
    }
}
