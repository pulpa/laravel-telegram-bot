<?php

namespace Pulpa\Telegram\Bot;

use Pulpa\Telegram\Bot\Exceptions\NoMessageException;

class Update
{
    /**
     * Input data from the webhook request.
     *
     * @var object
     */
    protected $data;

    /**
     * Type of messages that an update can contain.
     *
     * @var array
     */
    protected $messageTypes = [
        'message',
        'edited_message',
        'channel_post',
        'edited_channel_post',
    ];

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
        if (property_exists($this->data, $property)) {
            return $this->data->$property;
        }

        return null;
    }

    /**
     * Check if the update is a command.
     *
     * @return boolean
     */
    public function isCommand()
    {
        if ( ! $this->hasTextMessage()) {
            return false;
        }

        $regexp = '^/[a-z0-9_]+';

        if ($this->getMessage()->chat->type == 'group') {
            $regexp .= '@'.config('bot.name');
        }

        return (boolean) preg_match("|{$regexp}|", $this->getMessage()->text);
    }

    /**
     * Get the command name in this update.
     *
     * @return string
     */
    public function commandName()
    {
        if ( ! preg_match('|^/([a-z0-9_]+)|', $this->getMessage()->text, $matches)) {
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
        $text = explode(' ', $this->getMessage()->text);
        array_shift($text);

        return trim(implode(' ', $text));
    }

    /**
     * Checks if this update contains a message.
     *
     * @return boolean
     */
    public function hasMessage()
    {
        return $this->messageType() !== null;
    }

    /**
     * Checks if this update contains a text message.
     *
     * @return boolean
     */
    public function hasTextMessage()
    {
        return $this->hasMessage() && property_exists($this->getMessage(), 'text');
    }

    /**
     * Ge the message object in this update.
     *
     * @return object
     */
    public function getMessage()
    {
        if (is_null($messageType = $this->messageType())) {
            throw new NoMessageException;
        }

        return $this->data->$messageType;
    }

    /**
     * Returns the message type in this update, if this update does not have
     * any message then returns null.
     *
     * @return string|null
     */
    public function messageType()
    {
        foreach ($this->messageTypes as $messageType) {
            if (property_exists($this->data, $messageType)) {
                return $messageType;
            }
        }

        return null;
    }
}
