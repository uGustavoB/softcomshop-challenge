<?php

namespace App\Repositories;

use App\Models\Clientes;

class ClientesRepository
{
    private $model;

    public function __construct() {
        $this->model = new Clientes();
    }

    public function listagem() {
        return $this->model->all();
    }
    public function capturar($id) {
        return $this->model->find( $id );
    }

    public function salvar($dados) {
        $id = null;

        if (isset($dados["id"])) {
            $id =  $dados["id"];
        }

        $model = $this->model->findOrNew($id);

        $model->fill($dados);
        $model->save();

        return $model;
    }

    public function deletar($id) {
        $this->model->find($id)->delete();

        return true;
    }
}
