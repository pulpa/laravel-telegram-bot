<?php

return [
    'name' => 'my_bot',
    'token' => env('TELEGRAM_BOT_TOKEN'),
    'controller' => 'App\Http\Controllers\BotController',
];
