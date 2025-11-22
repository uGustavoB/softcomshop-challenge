<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('teste');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::group([
    'middleware' => 'auth',
    'prefix' => 'clientes'
], function() {
    Route::get("/", "ClientesController@listagem");
    Route::get("/novo", "ClientesController@formulario");
    Route::get("/{id}/editar/", "ClientesController@formulario");
    Route::post("/salvar", "ClientesController@salvar");
    Route::delete("/{id}/deletar", "ClientesController@deletar");
});
