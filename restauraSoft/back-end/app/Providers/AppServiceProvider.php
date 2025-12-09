<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $useCaseBasePath = app_path('UseCases');

        // Procura interfaces no padrão: I{Acao}UseCase.php
        $interfaces = glob($useCaseBasePath . '/**/I*UseCase.php');

        foreach ($interfaces as $interfacePath) {
            $interface = $this->convertPathToNamespace($interfacePath);

            // Converte "I{Acao}UseCase" em "{Acao}UseCase"
            $implementation = str_replace(
                'I',
                '',
                $interface
            );

            if (class_exists($implementation)) {
                $this->app->bind($interface, $implementation);
            }
        }
    }

    /**
     * Converte caminho de arquivo em namespace.
     */
    private function convertPathToNamespace(string $path): string
    {
        $relative = str_replace(app_path(), 'App', $path);
        $relative = str_replace('/', '\\', $relative);

        return str_replace('.php', '', $relative);
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
