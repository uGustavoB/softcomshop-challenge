<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PratoRequest extends FormRequest
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
            "nome" => "required|string|max:255",
            "descricao" => "nullable|string",
            "preco" => "required|numeric|min:0",
            "imagem" => "nullable|string",
            "categoria_id" => "nullable|integer|exists:categorias,id",
            "ativo" => "nullable|boolean",
        ];
    }
}
