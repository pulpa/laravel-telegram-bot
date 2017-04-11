<?php

namespace Pulpa\LaravelTelegramBot\Controllers;

use Illuminate\Http\Request;
use Pulpa\LaravelTelegramBot\Update;

class BotController
{
    public function index()
    {
        return "I'm a bot. [o_o]";
    }

    /**
     * Receives the request for the webhook.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $update = new Update($request->all());

        if ($update->isCommand()) {
            event('bot.command.'.$update->commandName(), $update);
        }
    }
}
