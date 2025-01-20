<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
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
    public function boot(): void
    {
        Vite::prefetch(concurrency: 3);

        Gate::define('cadastro-usuario', function(User $user) {
            return $user->perfil === 'T' || $user->perfil === 'S';
        });

        Gate::define('editar-usuario', function(User $user, User $editavel) {
            if ($user->perfil == 'T') {
                return true;
            } else if ($user->perfil == 'S' && $editavel->perfil == 'A') {
                return true;
            }

            return false;
        });
    }
}
