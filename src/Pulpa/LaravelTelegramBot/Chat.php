<?php

namespace Pulpa\LaravelTelegramBot;

use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    protected $table = 'bot_chats';
    protected $fillable = ['id', 'type'];

    /**
     * Register a new chat if it does not exists.
     *
     * @param  object $chat
     * @return \Pulpa\LaravelTelegramBot\Chat
     */
    protected function register($chat)
    {
        return static::firstOrCreate([
            'id' => $chat->id,
            'type' => $chat->type,
        ]);
    }
}