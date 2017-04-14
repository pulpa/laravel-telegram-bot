<?php

namespace Pulpa\Telegram\Bot\Testing;

use Pulpa\Telegram\Bot\Update;
use Pulpa\Telegram\Bot\Http\Controllers\Controller;

class BotTestController extends Controller
{
    public function catchAll(Update $update)
    {
        return 'catch all';
    }

    public function testCommand()
    {
        return 'method called';
    }

    public function returnArguments(Update $update)
    {
        return $update->commandArguments();
    }
}
