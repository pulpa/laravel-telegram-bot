<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Bot Name
    |--------------------------------------------------------------------------
    |
    | Here you specify the bot's name, this is necessary for the framework to
    | recognize bot commands from a group chat.
    */
    'name' => 'my_bot',


    /*
    |--------------------------------------------------------------------------
    | Bot Token
    |--------------------------------------------------------------------------
    |
    | This is the bot token, make sure to mantain it secure in your .env file.
    */
    'token' => env('TELEGRAM_BOT_TOKEN'),

    /*
    |--------------------------------------------------------------------------
    | Bot Controller
    |--------------------------------------------------------------------------
    |
    | This controller will receive all the updates coming from Telegram to
    | your webhook, it must extends from class:
    | Pulpa\Telegram\Bot\Http\Controllers\Controller
    */
    'controller' => App\Http\Controllers\BotController::class,

    /*
    |--------------------------------------------------------------------------
    | Debug Log
    |--------------------------------------------------------------------------
    |
    | Set this to true to log all incoming updates while your app is on debug
    | mode.
    */
    'debug_log' => true,
];
