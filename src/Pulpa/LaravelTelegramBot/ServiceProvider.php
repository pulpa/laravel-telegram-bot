<?php

namespace Pulpa\LaravelTelegramBot;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider as LaravelServiceProvider;

class ServiceProvider extends LaravelServiceProvider
{
    public function boot()
    {
        $this->registerRoutes();
        $this->registerPublishable();
    }

    public function register()
    {
        $this->mergeConfig();

        $this->app->singleton('pulpa_laravel_telegram_bot', function () {
            return new Bot(config('bot.token'));
        });
    }

    protected function registerRoutes()
    {
        $route = config('bot.token');

        Route::namespace('Pulpa\LaravelTelegramBot\Controllers')
            ->group(function () use ($route) {
                Route::get($route, 'BotController@index')->name('bot.index');
                Route::post($route, 'BotController@store')->name('bot.store');
            });

    }

    protected function registerPublishable()
    {
        $this->publishes([
            __DIR__.'/../../config/bot.php' => config_path('bot.php'),
        ]);
    }

    protected function mergeConfig()
    {
        $this->mergeConfigFrom(__DIR__.'/../../config/bot.php', 'bot');
    }
}
