<?php

namespace Pulpa\LaravelTelegramBot\Testing\Feature;

use Illuminate\Support\Facades\Route;
use Pulpa\LaravelTelegramBot\Testing\TestCase;

class ServiceProviderTest extends TestCase
{
    public function test_it_registers_routes()
    {
        $this->assertTrue(Route::has('bot.index'), 'Route bot.index exists');
        $this->assertTrue(Route::has('bot.store'), 'Route bot.store exists');
    }
}
