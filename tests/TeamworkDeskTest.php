<?php

namespace DigitalEquation\Teamwork\Tests;

use DigitalEquation\Teamwork\Exceptions\TeamworkHttpException;
use DigitalEquation\Teamwork\Exceptions\TeamworkInboxException;
use DigitalEquation\Teamwork\Exceptions\TeamworkUploadException;
use DigitalEquation\Teamwork\Services\Desk;
use DigitalEquation\Teamwork\Teamwork;

class TeamworkDeskTest extends TeamworkTestCase
{
    /** @test */
    public function it_should_throw_an_http_exception_on_user_request()
    {
        $this->app['config']->set('teamwork.desk.domain', 'undefined');

        $this->expectException(TeamworkHttpException::class);
        (new Teamwork)->desk()->me();
    }

    /** @test */
    public function it_should_return_the_logged_in_user()
    {
        $body     = file_get_contents(__DIR__ . '/Mock/Me/response-body.json');
        $client   = $this->mockClient(200, $body);
        $response = new Desk($client);

        $this->assertEquals($body, $response->me());
    }

    /** @test */
    public function it_should_throw_an_http_exception_on_inboxes_request()
    {
        $this->app['config']->set('teamwork.desk.domain', 'undefined');

        $this->expectException(TeamworkHttpException::class);
        (new Teamwork)->desk()->inboxes();
    }

    /** @test */
    public function it_should_return_an_array_of_inboxes()
    {
        $body     = file_get_contents(__DIR__ . '/Mock/Desk/inboxes-response.json');
        $client   = $this->mockClient(200, $body);
        $response = new Desk($client);

        $this->assertEquals($body, $response->inboxes());
    }

    /** @test */
    public function it_should_throw_an_inbox_exception()
    {
        $this->expectException(TeamworkInboxException::class);

        $body     = file_get_contents(__DIR__ . '/Mock/Desk/inboxes-response.json');
        $client   = $this->mockClient(200, $body);
        $response = new Desk($client);
        $response->inbox('undefined-inbox-name');
    }

    /** @test */
    public function it_should_throw_an_http_exception_on_inbox_request()
    {
        $this->app['config']->set('teamwork.desk.domain', 'undefined');

        $this->expectException(TeamworkHttpException::class);
        $this->teamwork->desk()->inbox('undefined');
    }

    /** @test */
    public function it_should_return_the_inbox_data()
    {
        $body     = file_get_contents(__DIR__ . '/Mock/Desk/inboxes-response.json');
        $client   = $this->mockClient(200, $body);
        $response = new Desk($client);

        $inboxResponse = file_get_contents(__DIR__ . '/Mock/Desk/inbox-response.json');
        $this->assertEquals($inboxResponse, $response->inbox('Inbox 1'));
    }

    /** @test */
    public function it_should_throw_an_upload_exception_on_post_upload_request()
    {
        $this->expectException(TeamworkUploadException::class);

        (new Teamwork)->desk()->upload(24234, '');
    }

    /** @test */
    public function it_should_throw_an_http_exception_on_post_upload_request()
    {
        $this->app['config']->set('teamwork.desk.domain', 'undefined');

        $this->expectException(TeamworkHttpException::class);

        $request = $this->getUploadFileRequest('file');
        (new Teamwork)->desk()->upload(423423, $request->file);
    }

    /** @test */
    public function it_should_upload_a_file_and_return_the_attachment_id()
    {
        $request  = $this->getUploadFileRequest('file');
        $body     = file_get_contents(__DIR__ . '/Mock/Tickets/upload-data.json');
        $client   = $this->mockClient(200, $body);
        $response = new Desk($client);

        $this->assertEquals(6546, $response->upload(6546545, $request->file));
    }
}