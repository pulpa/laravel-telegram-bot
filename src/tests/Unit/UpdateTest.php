<?php

namespace Pulpa\LaravelTelegramBot\Testing\Unit;

use Pulpa\LaravelTelegramBot\Update;
use Pulpa\LaravelTelegramBot\Testing\TestCase;

class UpdateTest extends TestCase
{
    /** @test */
    public function a_private_message_has_a_command()
    {
        $update = new Update([
            'message' => [
                'text' => '/command',
                'chat' => [
                    'type' => 'private',
                ],
            ],
        ]);

        $this->assertTrue($update->isCommand('command'), 'Command without leading slash');
        $this->assertTrue($update->isCommand('/command'), 'Command with leading slash');
    }

    /** @test */
    public function a_group_message_has_a_command()
    {
        $this->fail('a group message has a command');
    }
}