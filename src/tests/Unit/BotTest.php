<?php

namespace Pulpa\LaravelTelegramBot\Testing\Unit;

use Pulpa\LaravelTelegramBot\Bot;
use Pulpa\LaravelTelegramBot\Testing\TestCase;

class BotTest extends TestCase
{
    /** @test */
    public function makes_options_for_application_json_request()
    {
        $bot = new Bot('token');

        $result = $bot->makeRequestOptions([
            'field' => 'value',
        ]);

        $this->assertEquals([
            'json' => ['field' => 'value'],
        ], $result);
    }

    /** @test */
    public function makes_options_for_multipart_form_data_request()
    {
        $bot = new Bot('token');
        $resource = fopen(__FILE__, 'r');

        $result = $bot->makeRequestOptions([
            'field' => 'value',
            'file' => $resource,
        ]);

        $this->assertEquals([
            'multipart' => [
                [
                    'name' => 'field',
                    'contents' => 'value',
                ],
                [
                    'name' => 'file',
                    'contents' => $resource,
                ],
            ]
        ], $result);
    }

    /** @test */
    public function detects_when_data_has_files()
    {
        $bot = new Bot('token');

        $result = $bot->hasFiles([
            'file' => fopen(__FILE__, 'r')
        ]);

        $this->assertTrue($result);
    }

    /** @test */
    public function detects_when_data_does_not_have_files()
    {
        $bot = new Bot('token');

        $result = $bot->hasFiles([
            'field' => 'value'
        ]);

        $this->assertFalse($result);
    }

    /** @test */
    public function format_data_as_multipart()
    {
        $bot = new Bot('token');
        $resource = fopen(__FILE__, 'r');

        $result = $bot->formatDataAsMultipart([
            'field' => 'value',
            'file' => $resource,
        ]);

        $this->assertEquals([
            [
                'name' => 'field',
                'contents' => 'value',
            ],
            [
                'name' => 'file',
                'contents' => $resource,
            ]
        ], $result);
    }
}