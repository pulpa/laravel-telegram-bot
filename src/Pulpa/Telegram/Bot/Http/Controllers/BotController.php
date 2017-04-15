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
        $this->logRequest();

        $method = 'catchAll';
        $controller = app(config('bot.controller'));

        if ($update->isCommand() && method_exists($controller, $update->commandMethodName())) {
            $method = $update->commandMethodName();
        }

        return $controller->$method($update);
    }

    /**
     * Log the request input.
     *
     * @param  \Pulpa\Telegram\Bot\Update  $update
     */
    private function logRequest()
    {
        if (env('APP_DEBUG') && config('bot.debug_log', false)) {
            \Log::debug('Update', request()->all());
        }
    }
}
