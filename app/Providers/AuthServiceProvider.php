<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Documento;
use App\Models\DocumentoExpediente;
use App\Policies\DocumentoPolicy;
use App\Policies\DocumentoExpedientePolicy;



class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Documento::class => DocumentoPolicy::class,
        DocumentoExpediente::class => DocumentoExpedientePolicy::class,
        // Model::class => ModelPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        Gate::define('ver-documento', function ($user, $documento) {
            if ($documento instanceof DocumentoExpediente) {
                return $user->can('verDocumento', $documento);
            }
            if ($documento instanceof Documento) {
                return $user->can('verDocumento', $documento);
            }
            return false;
        });
    }
}