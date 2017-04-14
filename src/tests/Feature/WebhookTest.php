<?php

namespace Pulpa\Telegram\Bot\Testin\Feature;

use Pulpa\Telegram\Bot\Testing\TestCase;
use Pulpa\Telegram\Bot\Testing\BotTestController;

class WebhookTest extends TestCase
{
    /** @test */
    public function call_method_catch_all()
    {
        config(['bot.controller' => BotTestController::class]);

        $input = [
            'message' => [
                'text' => 'Single message',
                'chat' => [ 'type' => 'private' ],
            ],
        ];

        $response = $this->json('POST', config('bot.token'), $input);

        $response->assertStatus(200);

        $this->assertEquals('catch all', $response->getContent());
    }

    /** @test */
    public function bot_command_triggers_a_specific_controller_method()
    {
        config(['bot.controller' => BotTestController::class]);

        $input = [
            'message' => [
                'text' => '/test_command',
                'chat' => [ 'type' => 'private' ],
            ],
        ];

        $response = $this->json('POST', config('bot.token'), $input);

        $response->assertStatus(200);

        $this->assertEquals('method called', $response->getContent());
    }

    /** @test */
    public function update_object_is_passed_to_controller_method()
    {
        config(['bot.controller' => BotTestController::class]);

        $input = [
            'message' => [
                'text' => '/return_arguments arguments',
                'chat' => ['type' => 'private'],
            ],
        ];

        $response = $this->json('POST', config('bot.token'), $input);

        $response->assertStatus(200);

        $this->assertEquals('arguments', $response->getContent());
    }
}
