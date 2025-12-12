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
use App\UseCases\Mesa\ListarMesa\IListarMesaUseCase;
use App\UseCases\Mesa\ListarMesa\ListarMesaUseCase;
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
