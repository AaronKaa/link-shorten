<?php

namespace App\Providers;

use App\Store\LinkStore;
use App\Store\MySqlStore;
use App\Store\RedisStore;
use App\Store\SQLiteStore;
use Illuminate\Support\ServiceProvider;

class ShortnerStoreServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(LinkStore::class, function () {
            $store = config('shorten.store');

            switch ($store) {
                case 'redis':
                    return new RedisStore();
                case 'sqlite':
                    return new SQLiteStore();
                case 'mysql':
                    return new MySqlStore();
                default:
                    throw new \Exception("Invalid shortner store configured.");
            }
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
