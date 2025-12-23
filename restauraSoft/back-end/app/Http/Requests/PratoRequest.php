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
        $rules = [
            "nome" => "required|string|max:255",
            "descricao" => "nullable|string",
            "preco" => "required|numeric|min:0",
//            "imagem" => "nullable|image|mimes:jpeg,png,jpg,gif|max:2048",
            "categoria_id" => "nullable|integer|exists:categorias,id",
            "ativo" => "nullable|boolean",
        ];

        if ($this->hasFile('imagem')) {
            $rules['imagem'] = 'image|mimes:jpeg,png,jpg,gif|max:2048';
        }

        if ($this->isMethod('put') || $this->isMethod('patch')) {
            $pratoId = $this->route('id');
            $rules['nome'] = 'required|string|max:255|unique:pratos,nome,' . $pratoId;
        } else {
            $rules['nome'] = 'required|string|max:255|unique:pratos,nome';
        }

        return $rules;
    }

    protected function prepareForValidation()
    {
        if ($this->has('ativo')) {
            $ativoValue = $this->input('ativo');

            if (is_string($ativoValue)) {
                $ativoValue = strtolower($ativoValue);
                $booleanValue = in_array($ativoValue, ['1', 'true', 'yes', 'on', 'ativo'], true);
                $this->merge(['ativo' => $booleanValue]);
            }
        }
    }
}
