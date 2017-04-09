<?php

namespace Pulpa\LaravelTelegramBot;

class Update
{
    protected $data;

    public function __construct($data)
    {
        $this->data = json_decode(json_encode($data));
    }

    public function isCommand($command)
    {
        $command = ltrim($command, '/');
        return starts_with($this->data->message->text, "/{$command}");
    }
}