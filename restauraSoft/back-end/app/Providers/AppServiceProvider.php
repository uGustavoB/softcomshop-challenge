<?php

namespace App\Providers;

use App\UseCases\Categoria\DeletarCategoria\DeletarCategoriaUseCase;
use App\UseCases\Categoria\DeletarCategoria\IDeletarCategoriaUseCase;
use App\UseCases\Categoria\EditarCategoria\EditarCategoriaUseCase;
use App\UseCases\Categoria\EditarCategoria\IEditarCategoriaUseCase;
use App\UseCases\Categoria\ListarCategorias\IListarCategoriasUseCase;
use App\UseCases\Categoria\ListarCategorias\ListarCategoriasUseCase;
use App\UseCases\Categoria\VerificarCategoria\IVerificarCategoriaUseCase;
use App\UseCases\Categoria\VerificarCategoria\VerificarCategoriaUseCase;
use App\UseCases\ItensPedido\CriarItemPedido\CriarItemPedidoUseCase;
use App\UseCases\ItensPedido\CriarItemPedido\ICriarItemPedidoUseCase;
use App\UseCases\ItensPedido\DeletarItemPedido\DeletarItemPedidoUseCase;
use App\UseCases\ItensPedido\DeletarItemPedido\IDeletarItemPedidoUseCase;
use App\UseCases\ItensPedido\EditarItemPedido\EditarItemPedidoUseCase;
use App\UseCases\ItensPedido\EditarItemPedido\IEditarItemPedidoUseCase;
use App\UseCases\ItensPedido\ListarItensPedido\IListarItensPedidoUseCase;
use App\UseCases\ItensPedido\ListarItensPedido\ListarItensPedidoUseCase;
use App\UseCases\Mesa\CriarMesa\CriarMesaUseCase;
use App\UseCases\Mesa\CriarMesa\ICriarMesaUseCase;
use App\UseCases\Mesa\DeletarMesa\DeletarMesaUseCase;
use App\UseCases\Mesa\DeletarMesa\IDeletarMesaUseCase;
use App\UseCases\Mesa\EditarMesa\EditarMesaUseCase;
use App\UseCases\Mesa\EditarMesa\IEditarMesaUseCase;
use App\UseCases\Mesa\ListarMesa\IListarMesaUseCase;
use App\UseCases\Mesa\ListarMesa\ListarMesaUseCase;
use App\UseCases\Pedido\CriarPedido\CriarPedidoUseCase;
use App\UseCases\Pedido\CriarPedido\ICriarPedidoUseCase;
use App\UseCases\Pedido\DeletarPedido\DeletarPedidoUseCase;
use App\UseCases\Pedido\DeletarPedido\IDeletarPedidoUseCase;
use App\UseCases\Pedido\EditarPedido\EditarPedidoUseCase;
use App\UseCases\Pedido\EditarPedido\IEditarPedidoUseCase;
use App\UseCases\Pedido\ListarPedidos\IListarPedidosUseCase;
use App\UseCases\Pedido\ListarPedidos\ListarPedidosUseCase;
use App\UseCases\Pratos\CriarPratos\CriarPratosUseCase;
use App\UseCases\Pratos\CriarPratos\ICriarPratosUseCase;
use App\UseCases\Pratos\DeletarPrato\DeletarPratoUseCase;
use App\UseCases\Pratos\DeletarPrato\IDeletarPratoUseCase;
use App\UseCases\Pratos\EditarPratos\EditarPratosUseCase;
use App\UseCases\Pratos\EditarPratos\IEditarPratosUseCase;
use App\UseCases\Pratos\ListarPratos\IListarPratosUseCase;
use App\UseCases\Pratos\ListarPratos\ListarPratosUseCase;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use App\UseCases\Categoria\CriarCategorias\CriarCategoriasUseCase;
use App\UseCases\Categoria\CriarCategorias\ICriarCategoriasUseCase;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
//      Pratos UseCases
        $this->app->bind(
            ICriarPratosUseCase::class,
            CriarPratosUseCase::class
        );
        $this->app->bind(
            IListarPratosUseCase::class,
            ListarPratosUseCase::class
        );
        $this->app->bind(
            IEditarPratosUseCase::class,
            EditarPratosUseCase::class
        );
        $this->app->bind(
            IDeletarPratoUseCase::class,
            DeletarPratoUseCase::class
        );

//      Categoria UseCases
        $this->app->bind(
            ICriarCategoriasUseCase::class,
            CriarCategoriasUseCase::class
        );
        $this->app->bind(
            IListarCategoriasUseCase::class,
            ListarCategoriasUseCase::class
        );
        $this->app->bind(
            IEditarCategoriaUseCase::class,
            EditarCategoriaUseCase::class
        );
        $this->app->bind(
            IDeletarCategoriaUseCase::class,
            DeletarCategoriaUseCase::class
        );
        $this->app->bind(
            IVerificarCategoriaUseCase::class,
            VerificarCategoriaUseCase::class
        );

//      Mesa
        $this->app->bind(
            IListarMesaUseCase::class,
            ListarMesaUseCase::class
        );
        $this->app->bind(
            ICriarMesaUseCase::class,
            CriarMesaUseCase::class
        );
        $this->app->bind(
            IEditarMesaUseCase::class,
            EditarMesaUseCase::class
        );
        $this->app->bind(
            IDeletarMesaUseCase::class,
            DeletarMesaUseCase::class
        );

//      Pedido
        $this->app->bind(
            IListarPedidosUseCase::class,
            ListarPedidosUseCase::class
        );
        $this->app->bind(
            ICriarPedidoUseCase::class,
            CriarPedidoUseCase::class
        );
        $this->app->bind(
            IEditarPedidoUseCase::class,
            EditarPedidoUseCase::class
        );
        $this->app->bind(
            IDeletarPedidoUseCase::class,
            DeletarPedidoUseCase::class
        );

//      Itens de pedido
        $this->app->bind(
            IListarItensPedidoUseCase::class,
            ListarItensPedidoUseCase::class
        );
        $this->app->bind(
            ICriarItemPedidoUseCase::class,
            CriarItemPedidoUseCase::class
        );
        $this->app->bind(
            IEditarItemPedidoUseCase::class,
            EditarItemPedidoUseCase::class
        );
        $this->app->bind(
            IDeletarItemPedidoUseCase::class,
            DeletarItemPedidoUseCase::class
        );
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
    }
}
