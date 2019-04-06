<?php

namespace DigitalEquation\Teamwork\Tests;

use DigitalEquation\Teamwork\Exceptions\TeamworkHttpException;
use DigitalEquation\Teamwork\Exceptions\TeamworkParameterException;
use DigitalEquation\Teamwork\Services\Tickets;
use DigitalEquation\Teamwork\Teamwork;

class TeamworkTicketsTest extends TeamworkTestCase
{
    /** @test */
    public function it_should_throw_an_http_exception_on_priorities_request()
    {
        $this->app['config']->set('teamwork.desk.domain', 'undefined');

        $this->expectException(TeamworkHttpException::class);
        (new Teamwork)->tickets()->priorities();
    }

    /** @test */
    public function it_should_return_all_priorities()
    {
        $body     = file_get_contents(__DIR__ . '/Mock/Tickets/priorities-response.json');
        $client   = $this->mockClient(200, $body);
        $response = new Tickets($client);

        $this->assertEquals($body, $response->priorities());
    }

    /** @test */
    public function it_should_throw_an_http_exception_on_customer_tickets_request()
    {
        $this->app['config']->set('teamwork.desk.domain', 'undefined');

        $this->expectException(TeamworkHttpException::class);
        (new Teamwork)->tickets()->customer(52);
    }

    /** @test */
    public function it_should_return_a_list_of_customer_tickets()
    {
        $body     = file_get_contents(__DIR__ . '/Mock/Tickets/customer-tickets-response.json');
        $client   = $this->mockClient(200, $body);
        $response = new Tickets($client);

        $this->assertEquals($body, $response->customer(529245));
    }

    /** @test */
    public function it_should_throw_an_http_exception_on_ticket_request()
    {
        $this->app['config']->set('teamwork.desk.domain', 'undefined');

        $this->expectException(TeamworkHttpException::class);
        (new Teamwork)->tickets()->ticket(6545);
    }

    /** @test */
    public function it_should_return_a_ticket()
    {
        $body     = file_get_contents(__DIR__ . '/Mock/Tickets/ticket-response.json');
        $client   = $this->mockClient(200, $body);
        $response = new Tickets($client);

        $this->assertEquals($body, $response->ticket(6546545));
    }

    /** @test */
    public function it_should_throw_an_http_exception_on_create_ticket_request()
    {
        $this->app['config']->set('teamwork.desk.domain', 'undefined');

        $this->expectException(TeamworkHttpException::class);
        (new Teamwork)->tickets()->post([]);
    }

    /** @test */
    public function it_should_create_a_ticket()
    {
        $data = [
            'assignedTo'          => 5465,
            'inboxId'             => 5545,
            'tags'                => 'Test ticket',
            'priority'            => 'low',
            'status'              => 'active',
            'source'              => 'Email (Manual)',
            'customerFirstName'   => 'Test',
            'customerLastName'    => 'User',
            'customerEmail'       => 'test.user@email.com',
            'customerPhoneNumber' => '',
            'subject'             => 'TEST',
            'previewTest'         => 'This is an API test.',
            'message'             => 'Ths is an API test so please ignore this ticket.',
        ];

        $body     = file_get_contents(__DIR__ . '/Mock/Tickets/create-response.json');
        $client   = $this->mockClient(200, $body);
        $response = new Tickets($client);

        $this->assertEquals($body, $response->post($data));
    }

    /** @test */
    public function it_should_throw_an_parameter_exception_on_ticket_reply_request()
    {
        $this->expectException(TeamworkParameterException::class);
        (new Teamwork)->tickets()->reply([]);
    }

    /** @test */
    public function it_should_throw_an_http_exception_on_ticket_reply_request()
    {
        $this->app['config']->set('teamwork.desk.domain', 'undefined');

        $this->expectException(TeamworkHttpException::class);
        (new Teamwork)->tickets()->reply(['ticketId' => 1]);
    }

    /** @test */
    public function it_should_post_a_reply_and_return_back_the_ticket()
    {
        $body     = file_get_contents(__DIR__ . '/Mock/Tickets/ticket-reply-response.json');
        $client   = $this->mockClient(200, $body);
        $response = new Tickets($client);

        $this->assertEquals($body, $response->reply([
            'ticketId'   => 2201568,
            'body'       => 'Reply TEST on ticket.',
            'customerId' => 65465,
        ]));
    }
}
