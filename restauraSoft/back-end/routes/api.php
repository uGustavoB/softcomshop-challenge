<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\PratoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('logout', 'logout');
    Route::post('refresh', 'refresh');

});

Route::group([
    'middleware' => 'jwt.auth',
   'prefix' => 'prato'
], function () {
    Route::get('/', 'App\Http\Controllers\PratoController@listagem');
    Route::post('/', 'App\Http\Controllers\PratoController@cadastrar');
    Route::put('/{id}', 'App\Http\Controllers\PratoController@editar');
    Route::delete('/{id}', 'App\Http\Controllers\PratoController@deletar');
});

Route::group([
    'middleware' => 'jwt.auth',
    'prefix' => 'categoria'
], function () {
    Route::get('/', 'App\Http\Controllers\CategoriaController@listagem');
    Route::post('/', 'App\Http\Controllers\CategoriaController@cadastrar');
    Route::put('/{id}', 'App\Http\Controllers\CategoriaController@editar');
    Route::delete('/{id}', 'App\Http\Controllers\CategoriaController@deletar');
});

Route::group([
    'middleware' => 'jwt.auth',
    'prefix' => 'mesa'
], function () {
    Route::get('/', 'App\Http\Controllers\MesaController@listagem');
    Route::post('/', 'App\Http\Controllers\MesaController@cadastrar');
    Route::put('/{id}', 'App\Http\Controllers\MesaController@editar');
    Route::delete('/{id}', 'App\Http\Controllers\MesaController@deletar');
});

Route::group([
    'middleware' => 'jwt.auth',
    'prefix' => 'pedido'
], function () {
    Route::get('/', 'App\Http\Controllers\PedidoController@listagem');
    Route::post('/', 'App\Http\Controllers\PedidoController@cadastrar');
    Route::put('/{id}', 'App\Http\Controllers\PedidoController@editar');
    Route::delete('/{id}', 'App\Http\Controllers\PedidoController@deletar');
});

Route::group([
    'middleware' => 'jwt.auth',
    'prefix' => 'pedido'
], function () {
    Route::get('/{id}/itens', 'App\Http\Controllers\PedidoItemController@listagem');
    Route::post('/{id}/itens', 'App\Http\Controllers\PedidoItemController@cadastrar');
    Route::put('/{id}/itens/{itemId}', 'App\Http\Controllers\PedidoItemController@editar');
    Route::delete('/{id}/itens/{itemId}', 'App\Http\Controllers\PedidoItemController@deletar');
});


//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});
