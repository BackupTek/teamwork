<?php

namespace DigitalEquation\Teamwork;

use DigitalEquation\Teamwork\Services\Desk;
use DigitalEquation\Teamwork\Services\HelpDocs;
use DigitalEquation\Teamwork\Services\Tickets;
use GuzzleHttp\Client;

class Teamwork
{
    private ?string $apiKey = null;

    private ?string $domain = null;

    public function desk(): Desk
    {
        return new Desk($this->client());
    }

    public function helpDocs(): HelpDocs
    {
        return new HelpDocs($this->client());
    }

    public function tickets(): Tickets
    {
        return new Tickets($this->client());
    }

    public function setCredentials(string $apiKey, string $domain): void
    {
        $this->apiKey = $apiKey;
        $this->domain = $domain;
    }

    private function getCredentials(): array
    {
        return [
            'api_key' => $this->apiKey ?: config('teamwork.desk.key'),
            'domain'  => $this->domain ?: config('teamwork.desk.domain'),
        ];
    }

    private function client(): Client
    {
        $credentials = $this->getCredentials();

        return new Client([
            'base_uri' => sprintf('https://%s.teamwork.com/desk/v1/', $credentials['domain']),
            'auth'     => [$credentials['api_key'], ''],
        ]);
    }
}
