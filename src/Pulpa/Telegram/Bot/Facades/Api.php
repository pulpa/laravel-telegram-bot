<?php

namespace Pulpa\Telegram\Bot\Facades;

use Illuminate\Support\Facades\Facade;

class Api extends Facade
{
    public static function getFacadeAccessor()
    {
        return \Pulpa\Telegram\Bot\Api::class;
    }
}
