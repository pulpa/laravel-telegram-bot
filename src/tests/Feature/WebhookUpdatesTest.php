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

    /** @test */
    public function registers_a_new_chat()
    {
        $update = [
            'message' => [
                'chat' => ['id' => 123, 'type' => 'private']
            ]
        ];
        Bot::shouldReceive('sayHello')->once()->with(123);

        $this->json('POST', config('bot.token'), $update)->assertStatus(201);

        $this->assertDatabaseHas('bot_chats', [
            'id' => 123,
            'type' => 'private'
        ]);
    }
}