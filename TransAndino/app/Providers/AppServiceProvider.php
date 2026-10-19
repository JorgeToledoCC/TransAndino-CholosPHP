<?php

namespace App\Providers;

use App\Models\Bus;
use App\Models\Departure;
use App\Models\Route;
use App\Models\Ticket;
use App\Models\User;
use App\Observers\ActivityObserver;
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
        // Historial de actividad: todo lo que se crea, actualiza o elimina.
        foreach ([Route::class, Bus::class, Departure::class, Ticket::class, User::class] as $modelo) {
            $modelo::observe(ActivityObserver::class);
        }
    }
}
