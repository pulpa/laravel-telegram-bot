<?php

namespace Pulpa\LaravelTelegramBot\Testing\Feature;

use Pulpa\LaravelTelegramBot\Testing\TestCase;

class RoutesTest extends TestCase
{
    public function test_index_route()
    {
        $this->get(config('bot.token'))
            ->assertStatus(200)
            ->assertSee("I'm a bot. [o_o]");
    }
}
