<?php

namespace DigitalEquation\Teamwork;

use DigitalEquation\Teamwork\Services\Desk;
use DigitalEquation\Teamwork\Services\HelpDocs;
use DigitalEquation\Teamwork\Services\Tickets;
use GuzzleHttp\Client;

class Teamwork
{
    /**
     * @var \GuzzleHttp\Client
     */
    protected $client;

    /**
     * ApiClient constructor.
     */
    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => sprintf('https://%s.teamwork.com/desk/v1/', config('teamwork.desk.domain')),
            'auth'     => [config('teamwork.desk.key'), ''],
        ]);
    }

    /**
     * Teamwork Desk
     *
     * @return Desk
     */
    public function desk(): Desk
    {
        return new Desk($this->client);
    }

    /**
     * Teamwork HelpDocs
     *
     * @return HelpDocs
     */
    public function helpDocs(): HelpDocs
    {
        return new HelpDocs($this->client);
    }

    /**
     * Teamwork Tickets
     *
     * @return Tickets
     */
    public function tickets(): Tickets
    {
        return new Tickets($this->client);
    }
}
