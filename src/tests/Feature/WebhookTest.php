<?php

namespace Pulpa\LaravelTelegramBot\Testin\Feature;

use Illuminate\Support\Facades\Event;
use Pulpa\LaravelTelegramBot\Testing\TestCase;

class WebhookTest extends TestCase
{
    /** @test */
    public function dispatch_command_for_private_chat()
    {
        Event::fake();

        $input = [
            'message' => [
                'text' => '/start',
                'chat' => ['id' => 123, 'type' => 'private'],
            ],
        ];

        $this->json('POST', config('bot.token'), $input)->assertStatus(200);

        Event::assertDispatched('bot.command.start', function ($event, $update) use ($input) {
            return $update->message->chat->id === $input['message']['chat']['id'];
        });
    }
}
