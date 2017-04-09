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
     * Send a request to the given API method.
     *
     * @param  string  $method
     * @param  array  $parameters
     * @return object
     */
    public function __call($method, $parameters)
    {
        $data = count($parameters) ? $parameters[0] : [];
        $httpMethod = starts_with($method, 'get') ? 'GET' : 'POST';

        return $this->call($httpMethod, $method, $data);
    }

    /**
     * Makes an HTTP request and return the JSON object form the response.
     *
     * @param  string  $httpMethod
     * @param  string  $apiMethod
     * @param  array  $data
     * @return object
     */
    public function call($httpMethod, $apiMethod, $data = [])
    {
        $httpMethod = strtolower($httpMethod);

        return json_decode(
            $this->http->$httpMethod($apiMethod, $this->makeRequestOptions($data))->getBody()
        );
    }

    /**
     * Return an array of options to use in a HTTP request.
     *
     * @param  array  $data
     * @return array
     */
    public function makeRequestOptions($data)
    {
        $options = [];

        if ( ! $this->hasFiles($data)) {
            $options['json'] = $data;
        } else {
            $options['multipart'] = $this->formatDataAsMultipart($data);
        }

        return $options;
    }

    /**
     * Format the given data array to an array compatible with a multipart
     * form data request.
     *
     * @param  array  $data
     * @return array
     */
    public function formatDataAsMultipart($data)
    {
        $multipart = [];

        foreach ($data as $name => $contents) {
            $multipart[] = compact('name', 'contents');
        }

        return $multipart;
    }

    /**
     * Checks if the given data contains files (resource).
     *
     * @param  array  $data
     * @return bool
     */
    public function hasFiles($data)
    {
        foreach ($data as $value) {
            if (is_resource($value)) {
                return true;
            }
        }

        return false;
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
        ]);
    }
}