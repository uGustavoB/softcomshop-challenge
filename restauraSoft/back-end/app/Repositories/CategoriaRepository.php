<?php

namespace App\Repositories;

use App\Models\Categoria;
use App\Models\Prato;
use JasonGuru\LaravelMakeRepository\Repository\BaseRepository;
//use Your Model

/**
 * Class CategoriaRepository.
 */
class CategoriaRepository extends BaseRepository
{

    public function __construct() {
        parent::__construct();
        $this->model = new Categoria();
    }

    /**
     * @return string
     *  Return the model
     */
    public function model()
    {
        return Categoria::class;
    }

    public function listagem() {
        return $this->model()::all();
    }

    public function capturar($id) {
        return $this->model()::find( $id );
    }

    public function salvar($dados) {
        $id = $dados['id'] ?? null;

        $model = $this->model()::findOrNew($id);

        $model->fill($dados);

        $model->save();

        return $model;
    }

    public function deletar($id) {
        $this->model()::find( $id )->delete();

        return true;
    }

    public function existeNome($nome)
    {
        return $this->model()::where('nome', $nome)->exists();
    }

    public function buscarPorId($id)
    {
        return $this->model()::find($id);
    }
}
