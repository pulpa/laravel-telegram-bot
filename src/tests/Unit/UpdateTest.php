<?php

namespace Pulpa\LaravelTelegramBot\Testing\Unit;

use Pulpa\LaravelTelegramBot\Update;
use Pulpa\LaravelTelegramBot\Testing\TestCase;

class UpdateTest extends TestCase
{
    /** @test */
    public function message_in_a_private_chat_is_a_command()
    {
        $update = new Update([
            'message' => [
                'text' => '/command_test_123',
                'chat' => [
                    'type' => 'private',
                ],
            ],
        ]);

        $this->assertTrue($update->isCommand());
    }

    /** @test */
    public function message_in_a_private_chat_is_not_a_command()
    {
        $update = new Update([
            'message' => [
                'text' => 'this is not a command',
                'chat' => [
                    'type' => 'private',
                ],
            ],
        ]);

        $this->assertFalse($update->isCommand());
    }

    /** @test */
    public function message_in_a_group_chat_is_a_command()
    {
        config(['bot.name' => 'test_bot']);
        $update = new Update([
            'message' => [
                'text' => '/command_test_123@test_bot',
                'chat' => ['type' => 'group'],
            ],
        ]);

        $this->assertTrue($update->isCommand());
    }

    /** @test */
    public function message_in_a_group_chat_is_not_a_command()
    {
        $update = new Update([
            'message' => [
                'text' => 'this is not a command',
                'chat' => ['type' => 'group'],
            ],
        ]);

        $this->assertFalse($update->isCommand());
    }

    /** @test */
    public function commands_in_a_group_chat_must_contains_the_bot_name()
    {
        $update = new Update([
            'message' => [
                'text' => '/command_test_123',
                'chat' => ['type' => 'group'],
            ],
        ]);

        $this->assertFalse($update->isCommand());
    }

    /** @test */
    public function access_data_properties_from_update_object()
    {
        $update = new Update([
            'property' => 'value'
        ]);

        $this->assertEquals('value', $update->property);
    }

    /** @test */
    public function get_command_payload()
    {
        $update = new Update([
            'message' => [
                'text' => '/command_test_123 command payload',
            ]
        ]);

        $this->assertEquals('command payload', $update->commandPayload());

        // Command with bot name.
        $update->message->text = '/command_test_123@test_bot command payload';

        $this->assertEquals('command payload', $update->commandPayload());
    }

    /** @test */
    public function get_command_name()
    {
        $update = new Update([
            'message' => [
                'text' => '/command_test_123 argument',
            ]
        ]);

        $this->assertEquals('command_test_123', $update->commandName());

        // Command with bot name.
        $update->message->text = '/command_test_123@test_bot';

        $this->assertEquals('command_test_123', $update->commandName());
    }

    /** @test */
    public function get_null_if_no_command_is_present()
    {
        $update = new Update([
            'message' => [
                'text' => 'no command is present',
            ]
        ]);

        $this->assertNull($update->commandName());
    }
}
