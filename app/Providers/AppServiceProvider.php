<?php

namespace App\Providers;

use App\Http\Livewire\Profile\DeleteUserForm;
use App\Http\Livewire\Profile\LogoutOtherBrowserSessionsForm;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

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
        // Registramos los componentes Livewire personalizados
        Livewire::component('profile.logout-other-browser-sessions-form', LogoutOtherBrowserSessionsForm::class);
        Livewire::component('profile.delete-user-form', DeleteUserForm::class);
    }
}
