<?php

namespace DigitalEquation\Teamwork\Tests;

use DigitalEquation\Teamwork\Teamwork;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Orchestra\Testbench\TestCase;

class TeamworkTestCase extends TestCase
{
    /**
     * @var \Illuminate\Foundation\Application
     */
    protected $app;

    /**
     * @var \DigitalEquation\Teamwork\Teamwork
     */
    protected $teamwork;

    /**
     * Define environment setup.
     *
     * @param \Illuminate\Foundation\Application $app
     *
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        // Setup the Teamwork domain and API Key
        $app['config']->set('teamwork.desk.domain', 'somedomain');
        $app['config']->set('teamwork.desk.key', '04983o4krjwlkhoirtht983uytkjhgkjfh');

        $this->app      = $app;
        $this->teamwork = new Teamwork();
    }

    /**
     * Build the request for file upload.
     *
     * @param string $fileName
     * @param bool   $multiple
     *
     * @return Request
     */
    protected function getUploadFileRequest($fileName, $multiple = false)
    {
        Storage::fake('avatars');

        if ($multiple) {
            $files = [
                $fileName => [
                    UploadedFile::fake()->image('image.jpg'),
                    UploadedFile::fake()->image('image2.jpg'),
                ],
            ];
        } else {
            $files = [$fileName => UploadedFile::fake()->image('image.jpg')];
        }

        return new Request(
            [],
            [],
            [],
            [],
            $files,
            ['CONTENT_TYPE' => 'application/json'],
            null
        );
    }

    /**
     * Build the client mock.
     *
     * @param $status
     * @param $body
     *
     * @return \GuzzleHttp\Client
     */
    protected function mockClient($status, $body)
    {
        $mock    = new MockHandler([new Response($status, [], $body)]);
        $handler = HandlerStack::create($mock);

        return new Client(['handler' => $handler]);
    }
}
