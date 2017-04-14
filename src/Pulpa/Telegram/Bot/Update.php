<?php

namespace Pulpa\Telegram\Bot;

class Update
{
    /**
     * Input data from the webhook request.
     *
     * @var object
     */
    protected $data;

    /**
     * @param  array  $data
     */
    public function __construct($data)
    {
        $this->data = json_decode(json_encode($data));
    }

    /**
     * Return the value from the data object.
     *
     * @param  string  $property
     * @return mixed
     */
    public function __get($property)
    {
        return $this->data->$property;
    }

    /**
     * Check if the update is a command.
     *
     * @return boolean
     */
    public function isCommand()
    {
        $regexp = '^/[a-z0-9_]+';

        if ($this->message->chat->type == 'group') {
            $regexp .= '@'.config('bot.name');
        }

        return (boolean) preg_match("|{$regexp}|", $this->message->text);
    }

    /**
     * Get the command name in this update.
     *
     * @return string
     */
    public function commandName()
    {
        if ( ! preg_match('|^/([a-z0-9_]+)|', $this->message->text, $matches)) {
            return null;
        }

        return $matches[1];
    }

    /**
     * Get the command name in camel case.
     *
     * @return string
     */
    public function commandMethodName()
    {
        return camel_case($this->commandName());
    }

    /**
     * Get the command arguments in this update.
     *
     * @return string
     */
    public function commandArguments()
    {
        $text = explode(' ', $this->message->text);
        array_shift($text);

        return trim(implode(' ', $text));
    }
}
