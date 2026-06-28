<?php

namespace App\Providers;

use App\Helpers\PermissionHelper;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Verifica se a pasta de imagens existe e a cria se não existir
        $imagesPath = storage_path('app/public/images');
        if (!file_exists($imagesPath)) {
            mkdir($imagesPath, 0755, true);
        }
        
        // Registrar diretivas Blade para verificação de permissões
        Blade::if('admin', function () {
            return PermissionHelper::isAdmin();
        });
        
        Blade::if('secretaria', function () {
            return PermissionHelper::isSecretaria();
        });
        
        Blade::if('canStructuralActions', function () {
            return PermissionHelper::canPerformStructuralActions();
        });
    }
}
