<?php

namespace Pulpa\LaravelTelegramBot\Facades;

use Illuminate\Support\Facades\Facade;

class Bot extends Facade
{
    public static function getFacadeAccessor()
    {
        return 'pulpa_laravel_telegram_bot';
    }
}