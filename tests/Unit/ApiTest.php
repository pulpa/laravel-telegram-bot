<?php

namespace Pulpa\Telegram\Bot\Testing\Unit;

use Pulpa\Telegram\Bot\Api;
use Pulpa\Telegram\Bot\Testing\TestCase;

class ApiTest extends TestCase
{
    /** @test */
    public function makes_options_for_application_json_request()
    {
        $api = new Api('token');

        $result = $api->makeRequestOptions([
            'field' => 'value',
        ]);

        $this->assertEquals([
            'json' => ['field' => 'value'],
        ], $result);
    }

    /** @test */
    public function makes_options_for_multipart_form_data_request()
    {
        $bot = new Api('token');
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
        $bot = new Api('token');

        $result = $bot->hasFiles([
            'file' => fopen(__FILE__, 'r')
        ]);

        $this->assertTrue($result);
    }

    /** @test */
    public function detects_when_data_does_not_have_files()
    {
        $bot = new Api('token');

        $result = $bot->hasFiles([
            'field' => 'value'
        ]);

        $this->assertFalse($result);
    }

    /** @test */
    public function format_data_as_multipart()
    {
        $bot = new Api('token');
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
