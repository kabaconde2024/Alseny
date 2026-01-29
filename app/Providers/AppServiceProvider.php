<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;
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
        // 1. Force le HTTPS en production pour corriger l'affichage CSS/JS
        if (config('app.env') === 'production' || env('APP_ENV') === 'production') {
            URL::forceScheme('https');
        }

        // 2. Définition des Gates
        Gate::define('delete-admin', function (User $authUser, User $targetUser) {
            // Le super admin uniquement
            if (!$authUser->is_super_admin) return false;

            // Empêcher la suppression de soi-même
            if ($authUser->id === $targetUser->id) return false;

            // On ne supprime que des admins
            return (bool) $targetUser->is_admin;
        });
    }
}