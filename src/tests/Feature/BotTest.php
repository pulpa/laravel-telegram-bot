<?php

namespace Pulpa\LaravelTelegramBot\Testing;

use Pulpa\LaravelTelegramBot\Bot;
use Pulpa\LaravelTelegramBot\Chat;

class BotTest extends TestCase
{
    /** @test */
    public function get_me()
    {
        $bot = new Bot(config('bot.token'));

        $response = $bot->getMe();

        $this->assertTrue($response->ok);
    }

    /** @test */
    public function send_message()
    {
        $chatId = env('TELEGRAM_CHAT_ID');
        $bot = new Bot(config('bot.token'));

        $response = $bot->sendMessage($chatId, 'Text message');

        $this->assertTrue($response->ok);
        $this->assertEquals('Text message', $response->result->text);
        $this->assertEquals($chatId, $response->result->chat->id);
    }
}