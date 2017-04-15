<?php

namespace Pulpa\Telegram\Bot\Testin\Feature;

use Pulpa\Telegram\Bot\Testing\TestCase;
use Pulpa\Telegram\Bot\Testing\BotTestController;

class WebhookTest extends TestCase
{
    /** @test */
    public function method_catch_all_is_called_when_no_command_is_specified()
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
    public function method_catch_all_is_called_when_the_update_does_not_have_a_text_message()
    {
        config(['bot.controller' => BotTestController::class]);

        $input = [
            'message' => [
                'chat' => ['type' => 'private'],
                'new_chat_member' => ['id' => 123 ],
            ],
        ];

        $response = $this->json('POST', config('bot.token'), $input);
        $response->assertStatus(200);
        $this->assertEquals('catch all', $response->getContent());
    }

    /** @test */
    public function method_catch_all_is_called_when_a_command_method_is_not_defined_in_the_controller()
    {
        config(['bot.controller' => BotTestController::class]);

        $input = [
            'message' => [
                'text' => '/command_not_defined',
                'chat' => [ 'type' => 'private' ],
            ],
        ];

        $response = $this->json('POST', config('bot.token'), $input);
        $response->assertStatus(200);

        $this->assertEquals('catch all', $response->getContent());
    }

    /** @test */
    public function specific_command_method_is_called()
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
    public function update_object_is_passed_to_command_method()
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
