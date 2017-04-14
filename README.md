# Laravel Telegram Bot

*This is a work in progress, feel free to watch this repo to stay tuned.*

### Requirements
* Laravel `5.4+`

### Installation

Install the package with composer:

```
composer require pulpa/laravel-telegram-bot
```

Add this service provider to your `config/app.php` file:

```
Pulpa\Telegram\Bot\Providers\ServiceProvider::class
```

Create a configuration file in `config/bot.php` and a controller
in `app\Http\Controllers\BotController.php` by running the
`vendor:publish` artisan command:

```
php artisan vendor:publish --provider="Pulpa\Telegram\Bot\Providers\ServiceProvider"
```

Open `config/bot.php` and set up your bot's name and token.

Once you finish your configuration, your bot's webhook URL will be available at:

```
yourdomain.com/<BotToken>
```

Where `<BotToken>` is obviously the bot token, duh!
Open the URL in your web browser and you should see a friendly message.

### Usage

The method `catchAll()` within your `BotController` class will receive all
the updates coming from Telegram to your webhook, it will receive a parameter
of type `Pulpa\Telegram\Bot\Update` that is just a simple wrapper of the
original update object coming from Telegram.

```php
use Pulpa\Telegram\Bot\Update;
use Pulpa\Telegram\Bot\Http\Controllers\Controller;

class BotController extends Controller
{
    public function catchAll(Update $update)
    {
        // Log the text message
        \Log::info($update->message->text);
    }
}
```

Define methods in your `BotController` that will handle the incoming bot
commands, for example, method `myBotCommand`  will be called when the
webhook recieves a command named `my_bot_command`.

```php
use App\Chat;
use Pulpa\Telegram\Bot\Update;
use Pulpa\Telegram\Bot\Http\Controllers\Controller;

class BotController extends Controller
{
    public function catchAll(Update $update)
    {
        // ...
    }

    public function start(Update $update)
    {
        // Register a new chat when command "start" is received.
        Chat::register($update->message->chat->id);
    }
}
```

If no method is defined for a command then the method `catchAll()` will be called instead.


*More documentation is in progress*
