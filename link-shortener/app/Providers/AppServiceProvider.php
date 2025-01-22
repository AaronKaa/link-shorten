<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Response;

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
        // this was made because the default behavior
        // of the json response is to add slashes
        Response::macro('jsonac', function (array $value) {
            return response()->json($value)->setEncodingOptions(JSON_UNESCAPED_SLASHES);
        });
    }
}
