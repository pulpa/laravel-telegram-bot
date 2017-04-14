<?php

namespace Pulpa\Telegram\Bot\Http\Controllers;

use Pulpa\Telegram\Bot\Update;

abstract class Controller
{
    abstract public function catchAll(Update $update);
}
