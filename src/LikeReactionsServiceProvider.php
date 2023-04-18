<?php

namespace Smuuzy\Laravel\Likes;

use Illuminate\Support\ServiceProvider;
use function dirname;

class LikeReactionsServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            dirname(__DIR__) . '/config/reactions.php' => config_path('reactions.php'),
        ], 'config');
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/reactions.php', 'reactions');
    }
}
