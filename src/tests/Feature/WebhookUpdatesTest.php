<?php

namespace Pulpa\LaravelTelegramBot\Testing;

use Pulpa\LaravelTelegramBot\Facades\Bot;

class WebhookUpdatesTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->artisan('migrate', ['--database' => 'testing']);
    }

    public function test_it_registers_a_new_chat()
    {
        $chatId = 12345;
        $update = [
            'message' => [
                'chat' => ['id' => $chatId, 'type' => 'private']
            ]
        ];

        Bot::shouldReceive('sayHello')->once()->with(12345);

        $this->json('post', config('bot.token'), $update)->assertStatus(200);

        $this->assertDatabaseHas('bot_chats', [
            'id' => 12345,
            'type' => 'private'
        ]);
    }
}