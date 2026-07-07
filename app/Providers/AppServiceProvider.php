<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

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
        // Railway meng-handle SSL di depan; paksa semua URL yang di-generate
        // (form action, aset, redirect) pakai https saat produksi biar tidak
        // muncul peringatan "form tidak aman".
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }
    }
}
