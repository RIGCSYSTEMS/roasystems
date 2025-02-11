<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Documento;
use App\Models\DocumentoExpediente;
use App\Models\User;
use App\Policies\DocumentoPolicy;


class AppServiceProvider extends ServiceProvider
{
    protected $policies = [
        Documento::class => DocumentoPolicy::class,
        DocumentoExpediente::class => DocumentoPolicy::class,
    ];
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('ver-documento', function (User $user, Documento $documento) {
            // Por ahora, permitimos que cualquier usuario autenticado vea los documentos
            return true;
        });
    }
}
