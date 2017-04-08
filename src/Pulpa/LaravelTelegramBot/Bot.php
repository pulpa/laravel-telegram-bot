<?php

namespace Pulpa\LaravelTelegramBot;

use GuzzleHttp\Client;

class Bot
{
    /**
     * @var string
     */
    protected $token;

    /**
     * @var \GuzzleHttp\Client
     */
    protected $http;

    public function __construct($token)
    {
        $this->token = $token;
        $this->http = $this->makeHttpClient();
    }

    public function sayHello(Chat $chat)
    {
        $this->sendMessage($chat->id, "Hello! I'm ready.");
    }

    public function sendMessage($chat, $text = null)
    {
        $body = is_array($chat) ? $chat : ['chat_id' => $chat, 'text' => $text];

        $response = $this->http->post('sendMessage', ['body' => json_encode($body)]);
    }

    protected function makeHttpClient()
    {
        return new Client([
            'base_uri' => "https://api.telegram.org/bot{$this->token}/",
            'headers' => ['content-type' => 'application/json'],
        ]);
    }
}