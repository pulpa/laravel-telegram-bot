<?php

namespace Pulpa\LaravelTelegramBot\Testing\Feature;

use Pulpa\LaravelTelegramBot\Bot;
use Pulpa\LaravelTelegramBot\Testing\TestCase;

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
    public function send_a_message_passing_an_array_as_parameter()
    {
        $bot = new Bot(config('bot.token'));

        $response = $bot->sendMessage([
            'chat_id' => env('TELEGRAM_CHAT_ID'),
            'text' => 'Text message',
        ]);

        $this->assertTrue($response->ok);
        $this->assertEquals('Text message', $response->result->text);
        $this->assertEquals(env('TELEGRAM_CHAT_ID'), $response->result->chat->id);
    }
}