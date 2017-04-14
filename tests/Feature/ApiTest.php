<?php

namespace Pulpa\Telegram\Bot\Testing\Feature;

use Pulpa\Telegram\Bot\Facades\Api;
use Pulpa\Telegram\Bot\Testing\TestCase;

class ApiTest extends TestCase
{
    /** @test */
    public function get_me()
    {
        $response = Api::getMe();

        $this->assertTrue($response->ok);
    }

    /** @test */
    public function send_message()
    {
        $response = Api::sendMessage([
            'chat_id' => env('TELEGRAM_CHAT_ID'),
            'text' => 'Text message',
        ]);

        $this->assertTrue($response->ok);
        $this->assertEquals('Text message', $response->result->text);
        $this->assertEquals(env('TELEGRAM_CHAT_ID'), $response->result->chat->id);
    }

    /** @test */
    public function send_photo()
    {
        $response = Api::sendPhoto([
            'chat_id' => env('TELEGRAM_CHAT_ID'),
            'photo' => fopen(__DIR__.'/../files/photo.jpg', 'r'),
            'caption' => 'Test sending a file',
        ]);

        $this->assertTrue($response->ok);
        $this->assertEquals(env('TELEGRAM_CHAT_ID'), $response->result->chat->id);
    }

    /** @test */
    public function send_photo_using_file_id()
    {
        $response = Api::sendPhoto([
            'chat_id' => env('TELEGRAM_CHAT_ID'),
            'photo' => 'AgADAwADqacxG2j8UU_Box8bVQQ0rcL0hjEABArbOXytYTad0AcBAAEC',
            'caption' => 'Test sending a file using file_id',
        ]);

        $this->assertTrue($response->ok);
        $this->assertEquals(env('TELEGRAM_CHAT_ID'), $response->result->chat->id);
    }
}
