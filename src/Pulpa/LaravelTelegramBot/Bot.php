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

    /**
     * Send a hello message to the given chat and return the JSON object from
     * the response.
     *
     * @param  int  $chat
     * @return object
     */
    public function sayHello($chat)
    {
        return $this->sendMessage($chat, "Hello! I'm ready.");
    }

    /**
     * Get basic information about the bot.
     *
     * @link   https://core.telegram.org/bots/api#getme
     * @return object
     */
    public function getMe()
    {
        return $this->call('GET', 'getMe');
    }

    /**
     * Send a message to the given chat.
     *
     * @param  int|array  $chat
     * @param  null|string  $text
     * @link   https://core.telegram.org/bots/api#sendmessage
     * @return mixed
     */
    public function sendMessage($chat, $text = null)
    {
        return $this->call('POST', 'sendMessage', is_array($chat) ? $chat : [
            'chat_id' => $chat,
            'text' => $text,
        ]);
    }

    /**
     * Makes an HTTP request and return the JSON object form the response.
     *
     * @param  string  $method
     * @param  string  $methodName
     * @param  array  $data
     * @return object
     */
    public function call($method, $methodName, $data = [])
    {
        $method = strtolower($method);

        return json_decode(
            $this->http->$method($methodName, ['body' => json_encode($data)])->getBody()
        );
    }

    /**
     * Return a HTTP Client instance ready to send requests.
     *
     * @return \GuzzleHttp\Client
     */
    protected function makeHttpClient()
    {
        return new Client([
            'base_uri' => "https://api.telegram.org/bot{$this->token}/",
            'headers' => ['content-type' => 'application/json'],
        ]);
    }
}