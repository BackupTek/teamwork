<?php

namespace DigitalEquation\Teamwork\Tests;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use Illuminate\Http\Request;
use GuzzleHttp\Psr7\Response;
use Illuminate\Http\UploadedFile;
use Orchestra\Testbench\TestCase;
use GuzzleHttp\Handler\MockHandler;
use DigitalEquation\Teamwork\Teamwork;
use Illuminate\Support\Facades\Storage;

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

        $this->app = $app;
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
        $mock = new MockHandler([new Response($status, [], $body)]);
        $handler = HandlerStack::create($mock);

        return new Client(['handler' => $handler]);
    }
}
