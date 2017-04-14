<?php

namespace Pulpa\Telegram\Bot\Http\Controllers;

use Illuminate\Http\Request;
use Pulpa\Telegram\Bot\Update;

class BotController
{
    public function index()
    {
        return "I'm a bot. [o_o]";
    }

    /**
     * Receives an update from telegram servers.
     *
     * @param  \Pulpa\Telegram\Bot\Update  $update
     * @return \Illuminate\Http\Response
     */
    public function store(Update $update)
    {
        $controller = app(config('bot.controller'));
        $method = $update->isCommand() ? $update->commandMethodName() : 'catchAll';

        return $controller->$method($update);
    }
}
