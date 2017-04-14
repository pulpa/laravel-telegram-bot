<?php

namespace Pulpa\Telegram\Bot\Providers;

use Pulpa\Telegram\Bot\Api;
use Pulpa\Telegram\Bot\Update;
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

        $this->app->singleton(Update::class, function () {
            return new Update(request()->all());
        });

        $this->app->singleton('pulpa_telegram_bot_api', function () {
            return new Api(config('bot.token'));
        });
    }

    protected function registerRoutes()
    {
        $route = config('bot.token');

        Route::namespace('Pulpa\Telegram\Bot\Http\Controllers')
            ->group(function () use ($route) {
                Route::get($route, 'BotController@index')->name('bot.index');
                Route::post($route, 'BotController@store')->name('bot.store');
            });

    }

    protected function registerPublishable()
    {
        $this->publishes([
            __DIR__.'/../../../../config/bot.php' => config_path('bot.php'),
        ]);
    }

    protected function mergeConfig()
    {
        $this->mergeConfigFrom(__DIR__.'/../../../../config/bot.php', 'bot');
    }
}
