<?php

namespace App\Providers;

use Filament\Facades\Filament;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {

    }


    public function boot(): void
    {
        Schema::disableForeignKeyConstraints(); // 👈 Desactiva las foreigns durante el boot
    }

}
