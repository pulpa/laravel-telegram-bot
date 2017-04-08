<?php

namespace Pulpa\LaravelTelegramBot\Controllers;

use Illuminate\Http\Request;
use Pulpa\LaravelTelegramBot\Chat;
use Pulpa\LaravelTelegramBot\Facades\Bot;

class BotController
{
    public function index()
    {
        return "Hello, I'm a bot. [o_o]";
    }

    public function store(Request $request)
    {
        $update = $this->getUpdateObjectFrom($request);

        $chat = Chat::register($update->message->chat);

        if ($chat->wasRecentlyCreated) {
            Bot::sayHello($chat->id);
        }
    }

    protected function getUpdateObjectFrom($request)
    {
        return json_decode(json_encode($request->all()));
    }
}