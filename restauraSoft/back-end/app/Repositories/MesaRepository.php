<?php

namespace App\Repositories;

use App\Models\Mesa;
use JasonGuru\LaravelMakeRepository\Repository\BaseRepository;
//use Your Model

/**
 * Class MesaRepository.
 */
class MesaRepository extends BaseRepository
{
    public function __construct() {
        parent::__construct();
        $this->model = new Mesa();
    }

    /**
     * @return string
     *  Return the model
     */
    public function model()
    {
        return Mesa::class;
    }

    public function listagem() {
        return $this->model()::all();
    }

    public function existeNome($nome)
    {
        return $this->model()::where('numero', $nome)->exists();
    }

    public function salvar(array $dados)
    {
        $id = $dados['id'] ?? null;

        $model = $this->model()::findOrNew($id);

        $model->fill($dados);

        $model->save();

        return $model;
    }
}
