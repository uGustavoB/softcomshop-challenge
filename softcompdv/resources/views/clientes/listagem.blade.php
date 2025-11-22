@extends("layouts.admin-app")

@section("content")

<div class="card">
        <div class="card-header">
            <h5 class="card-title">Listagem de clientes</h5>
            <div class="float-right">
                <a href="{!! url('/clientes/novo') !!}" class="btn btn-primary"><i class="fas fa-plus"></i> Novo cliente</i></a>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Nome</th>
                        <th scope="col">CPF</th>
                        <th scope="col">Email</th>
                        <th scope="col">Status</th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($clientes as $cliente)
                        <tr>
                            <th scope="row">{!! $cliente->id !!}</th>
                            <td>{!! $cliente->nome !!}</td>
                            <td>{!! $cliente->cpf !!}</td>
                            <td>{!! $cliente->email !!}</td>
                            <td>
                                @if ($cliente->ativo == 1)
                                    <span class="badge badge-success">Ativo</span>
                                @else
                                    <span class="badge badge-danger">Desativado</span>
                                @endif
                            </td>
                            <td>
                                <a href="{!! url("/clientes/{$cliente->id}/editar") !!}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-pen"></i></i>
                                </a>
                                <a href="{!! url("/clientes/{$cliente->id}/deletar") !!}" class="btn btn-danger btn-sm botao-excluir">
                                    <i class="fas fa-trash"></i></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

@endsection

@push("js")
    <script>
        $(document).ready(() => {
            $(".botao-excluir").on("click", (event) => {
                event.preventDefault();

                const url = $(event.currentTarget).attr("href");

                fetch(url, {
                    method: "DELETE",
                    headers: {
                        "X-CSRF-TOKEN": csrfToken,
                        "Accept": "application/json"
                    }
                }).then(async (response) => {
                    const dados = await response.json();

                    if (dados.status === true) {
                        toastr.success("O cliente foi deletado.", "Sucesso!!")

                        setTimeout(() => {
                            window.location.reload();
                        }, 3000);

                        return;
                    }

                    toast.error("Algo de errado aconteceu.", "Opss")

                    console.log(dados);
                })

                return false;
            });
        });
    </script>

@endpush
