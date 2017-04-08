<?php

namespace Pulpa\LaravelTelegramBot\Testing;

class RoutesTest extends TestCase
{
    public function test_index_route()
    {
        $this->get(config('bot.token'))
            ->assertStatus(200)
            ->assertSee("Hello, I'm a bot. [o_o]");
    }
}