<?php

namespace Pulpa\Telegram\Bot\Testing\Unit;

use Pulpa\Telegram\Bot\Update;
use Pulpa\Telegram\Bot\Testing\TestCase;

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
    public function get_command_arguments()
    {
        $update = new Update([
            'message' => [
                'text' => '/command_test_123 command arguments',
            ]
        ]);

        $this->assertEquals('command arguments', $update->commandArguments());

        // Command with bot name.
        $update->message->text = '/command_test_123@test_bot command arguments';

        $this->assertEquals('command arguments', $update->commandArguments());
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

    /** @test */
    public function a_command_name_is_converted_to_method_name()
    {
        $update = new Update([
            'message' => [
                'chat' => ['type' => 'private'],
            ],
        ]);

        $update->message->text = '/command';
        $this->assertEquals('command', $update->commandMethodName());

        $update->message->text = '/test_command';
        $this->assertEquals('testCommand', $update->commandMethodName());
    }
}
