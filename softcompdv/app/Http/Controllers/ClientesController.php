<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClientesRequest;
use App\Repositories\ClientesRepository;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;

class ClientesController extends Controller
{
    private $repository;

    public function __construct() {
        $this->repository = new ClientesRepository();
    }

    public function listagem()
    {
        $clientes = $this->repository->listagem();
        return view('clientes.listagem', [
            'clientes' => $clientes
        ]);
    }

    public function formulario(Request $request, $id = null)
    {
        $cliente = $this->repository->capturar($id);

        return view('clientes.formulario', [
            'cliente' => $cliente
        ]);
    }

    public function salvar(ClientesRequest $request)
    {
        $dados = $request->all();

        $cliente = $this->repository->salvar($dados);

        Toastr::success('Cliente cadastrado com sucesso', 'Success');

        return redirect()->to('/clientes');
    }

    public function deletar(Request $request, $id) {
        $retorno = $this->repository->deletar($id);
        return response()->json([
            "status" => $retorno
        ]);
    }
}
