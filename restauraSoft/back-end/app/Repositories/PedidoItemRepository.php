<?php

namespace App\Repositories;

use App\Models\PedidoItem;
use JasonGuru\LaravelMakeRepository\Repository\BaseRepository;

//use Your Model

/**
 * Class PedidoItemRepository.
 */
class PedidoItemRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct();
        $this->model = new PedidoItem();
    }

    /**
     * @return string
     *  Return the model
     */
    public function model()
    {
        return PedidoItem::class;
    }

    public function listagem()
    {
        return $this->model()::all();
    }

    public function capturar($id)
    {
        return $this->model()::find($id);
    }

    public function salvar($dados)
    {
        $id = $dados['id'] ?? null;

        $model = $this->model()::findOrNew($id);

        $model->fill($dados);

        $model->save();

        return $model;
    }

    public function deletar($id)
    {
        $this->model()::find($id)->delete();

        return true;
    }
}
