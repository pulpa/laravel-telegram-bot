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
     * @param  string  $methodName
     * @param  array  $parameters
     * @return object
     */
    public function __call($methodName, $parameters)
    {
        $data = count($parameters) ? $parameters[0] : [];
        $method = starts_with($methodName, 'get') ? 'GET' : 'POST';

        return $this->call($method, $methodName, $data);
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
            $this->http->$method($methodName, $this->makeRequestOptions($data))->getBody()
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
        $options = [
            'headers' => [
                'content-type' => 'application/json'
            ]
        ];

        if ( ! $this->hasFiles($data)) {
            $options['body'] = json_encode($data);
        } else {
            $options['headers']['content-type'] = 'multipart/form-data';
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

        foreach ($data as $key => $value) {
            $multipart[] = [
                'name' => $key,
                'contents' => $value,
            ];
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
        foreach ($data as $key => $value) {
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
            'headers' => ['content-type' => 'application/json'],
        ]);
    }
}