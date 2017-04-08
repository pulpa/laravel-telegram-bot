<?php

namespace Pulpa\LaravelTelegramBot\Testin\Featureg;

use Pulpa\LaravelTelegramBot\Facades\Bot;
use Pulpa\LaravelTelegramBot\Testing\TestCase;

class WebhookUpdatesTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->artisan('migrate', ['--database' => 'testing']);
    }

    public function test_it_registers_a_new_chat()
    {
        $update = [
            'message' => [
                'chat' => ['id' => 123, 'type' => 'private']
            ]
        ];
        Bot::shouldReceive('sayHello')->once()->with(123);

        $this->json('post', config('bot.token'), $update)->assertStatus(200);

        $this->assertDatabaseHas('bot_chats', [
            'id' => 123,
            'type' => 'private'
        ]);
    }
}