@extends("layouts.admin-app")

@section("content")

    <div class="card">
        <div class="card-header">
            <h5 class="card-title">Cadastro de cliente</h5>
            <div class="float-right">
                <a href="{!! url('/clientes/') !!}" class="btn btn-primary"><i class="fas fa-arrow-left"> Voltar para listagem</i></a>
            </div>
        </div>
        <div class="card-body">
            {!! Form::model($cliente, ['url' => url("clientes/salvar"), "method" => "POST"] ) !!}
            {!! Form::hidden("id") !!}
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            {!! Form::label('nome', 'Nome') !!}
                            {!! Form::text('nome', null, ["class" => "form-control"]) !!}
                            @error("nome")
                                <span class="text-danger">
                                    {!! $message !!}
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            {!! Form::label('cpf', 'CPF') !!}
                            {!! Form::text('cpf', null, ["class" => "form-control", "maxlength" => "11"]) !!}
                            @error("cpf")
                                <span class="text-danger">
                                    {!! $message !!}
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            {!! Form::label('email', 'Email') !!}
                            {!! Form::text('email', null, ["class" => "form-control"]) !!}
                            @error("email")
                                <span class="text-danger">
                                    {!! $message !!}
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            {!! Form::label('telefone', 'Telefone') !!}
                            {!! Form::text('telefone', null, ["class" => "form-control"]) !!}
                            @error("telefone")
                                <span class="text-danger">
                                    {!! $message !!}
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-success"><i class="fas fa-check"></i> Salvar cliente</button>
            {!! Form::close() !!}
        </div>
    </div>
@endsection
