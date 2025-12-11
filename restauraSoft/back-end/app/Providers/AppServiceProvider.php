<?php

namespace App\Providers;

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

        $this->app->bind(
            ICriarCategoriasUseCase::class,
            CriarCategoriasUseCase::class
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
