<?php

namespace Pulpa\Telegram\Bot\Testing\Unit;

use Pulpa\Telegram\Bot\Update;
use Pulpa\Telegram\Bot\Testing\TestCase;
use Pulpa\Telegram\Bot\Exceptions\NoMessageException;

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
    public function no_command_is_present_if_there_is_no_message()
    {
        $update = new Update([
            'update_id' => 1234567890,
        ]);

        $this->assertFalse($update->isCommand());
    }

    /** @test */
    public function no_command_is_present_if_there_is_no_text_message()
    {
        $update = new Update([
            'message' => [
                'chat' => ['type' => 'ANY TYPE'],
            ],
        ]);

        $this->assertFalse($update->isCommand());
    }

    /** @test */
    public function update_has_a_message_of_any_type()
    {
        $update = new Update([ 'message' => ['foo' => 'bar'] ]);
        $this->assertTrue($update->hasMessage());

        $update = new Update([ 'edited_message' => ['foo' => 'bar'] ]);
        $this->assertTrue($update->hasMessage());

        $update = new Update([ 'channel_post' => ['foo' => 'bar'] ]);
        $this->assertTrue($update->hasMessage());

        $update = new Update([ 'edited_channel_post' => ['foo' => 'bar'] ]);
        $this->assertTrue($update->hasMessage());

        $update = new Update([ 'no_message' => [] ]);
        $this->assertFalse($update->hasMessage());
    }

    /** @test */
    public function update_has_text_message()
    {
        $update = new Update([ 'message' => ['text' => 'hello'] ]);
        $this->assertTrue($update->hasTextMessage());

        $update = new Update([ 'message' => ['foo' => 'bar'] ]);
        $this->assertFalse($update->hasTextMessage());
    }

    /** @test */
    public function get_message()
    {
        $update = new Update([ 'message' => 'Message' ]);
        $this->assertEquals('Message', $update->getMessage());

        $update = new Update([ 'edited_message' => 'Edited Message' ]);
        $this->assertEquals('Edited Message', $update->getMessage());

        $update = new Update([ 'channel_post' => 'Channel Post' ]);
        $this->assertEquals('Channel Post', $update->getMessage());

        $update = new Update([ 'edited_channel_post' => 'Edited Channel Post' ]);
        $this->assertEquals('Edited Channel Post', $update->getMessage());

        $update = new Update(['no_message' => 'foo']);

        try {
            $update->getMessage();
        } catch(NoMessageException $e) {
            return;
        }

        $this->fail('Exception NoMessageException was not thrown');
    }

    /** @test */
    public function get_message_type()
    {
        $update = new Update([ 'message' => 'foo' ]);
        $this->assertEquals('message', $update->messageType());

        $update = new Update([ 'edited_message' => 'foo' ]);
        $this->assertEquals('edited_message', $update->messageType());

        $update = new Update([ 'channel_post' => 'foo' ]);
        $this->assertEquals('channel_post', $update->messageType());

        $update = new Update([ 'edited_channel_post' => 'foo' ]);
        $this->assertEquals('edited_channel_post', $update->messageType());

        $update = new Update([ 'no_message' => 'foo' ]);
        $this->assertNull($update->messageType());
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
        $update->getMessage()->text = '/command_test_123@test_bot command arguments';

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
        $update->getMessage()->text = '/command_test_123@test_bot';

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

        $update->getMessage()->text = '/command';
        $this->assertEquals('command', $update->commandMethodName());

        $update->getMessage()->text = '/test_command';
        $this->assertEquals('testCommand', $update->commandMethodName());
    }

    /** @test */
    public function access_update_properties_through_update_object()
    {
        $update = new Update(['field' => 'value']);

        $this->assertEquals('value', $update->field);
        $this->assertNull($update->undefined_field);
    }
}
