<?php

namespace App\Providers;

use App\UseCases\Categoria\EditarCategoria\EditarCategoriaUseCase;
use App\UseCases\Categoria\EditarCategoria\IEditarCategoriaUseCase;
use App\UseCases\Categoria\ListarCategorias\IListarCategoriasUseCase;
use App\UseCases\Categoria\ListarCategorias\ListarCategoriasUseCase;
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
