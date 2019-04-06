<?php

namespace DigitalEquation\Teamwork\Services;

use DigitalEquation\Teamwork\Exceptions\TeamworkHttpException;
use DigitalEquation\Teamwork\Exceptions\TeamworkParameterException;
use DigitalEquation\Teamwork\Exceptions\TeamworkUploadException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Stream;
use Illuminate\Support\Facades\File;

class Tickets
{
    /**
     * @var \GuzzleHttp\Client
     */
    private $client;

    /**
     * Tickets constructor.
     *
     * @param \GuzzleHttp\Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Get tickets priorities.
     *
     * @return string
     * @throws \DigitalEquation\Teamwork\Exceptions\TeamworkHttpException
     */
    public function priorities(): string
    {
        try {
            /** @var Response $response */
            $response = $this->client->get('ticketpriorities.json');
            /** @var Stream $body */
            $body = $response->getBody();

            return $body->getContents();
        } catch (ClientException $e) {
            throw new TeamworkHttpException($e->getMessage(), 400);
        }
    }

    /**
     * Get a list of tickets for a customer.
     *
     * @param int $customerId
     *
     * @return string
     * @throws \DigitalEquation\Teamwork\Exceptions\TeamworkHttpException
     */
    public function customer($customerId): string
    {
        try {
            /** @var Response $response */
            $response = $this->client->get(sprintf('customers/%s/previoustickets.json', $customerId));
            /** @var Stream $body */
            $body = $response->getBody();

            return $body->getContents();
        } catch (ClientException $e) {
            throw new TeamworkHttpException($e->getMessage(), 400);
        }
    }

    /**
     * Send a ticket to teamwork desk.
     *
     * @param array $data
     *
     * @return string
     * @throws \DigitalEquation\Teamwork\Exceptions\TeamworkHttpException
     */
    public function post($data): string
    {
        try {
            /** @var Response $response */
            $response = $this->client->post('tickets.json', [
                'form_params' => $data,
            ]);

            /** @var Stream $body */
            $body = $response->getBody();

            return $body->getContents();
        } catch (ClientException $e) {
            throw new TeamworkHttpException($e->getMessage(), 400);
        }
    }

    /**
     * Post a reply to a ticket.
     *
     * @param array $data
     *
     * @return string
     * @throws \DigitalEquation\Teamwork\Exceptions\TeamworkHttpException
     * @throws \DigitalEquation\Teamwork\Exceptions\TeamworkParameterException
     */
    public function reply(array $data): string
    {
        if (empty($data['ticketId'])) {
            throw new TeamworkParameterException('The `reply` method expects the passed array param to contain `ticketId`', 400);
        }

        try {
            /** @var Response $response */
            $response = $this->client->post(sprintf('tickets/%s.json', $data['ticketId']), [
                'form_params' => $data,
            ]);

            /** @var Stream $body */
            $body = $response->getBody();

            return $body->getContents();
        } catch (ClientException $e) {
            throw new TeamworkHttpException($e->getMessage());
        }
    }

    /**
     * Get ticket by id.
     *
     * @param int $ticketId
     *
     * @return string
     * @throws \DigitalEquation\Teamwork\Exceptions\TeamworkHttpException
     */
    public function ticket($ticketId): string
    {
        try {
            /** @var Response $response */
            $response = $this->client->get(sprintf('tickets/%s.json', $ticketId));
            /** @var Stream $body */
            $body = $response->getBody();

            return $body->getContents();
        } catch (ClientException $e) {
            throw new TeamworkHttpException($e->getMessage(), 400);
        }
    }

    /**
     * Upload files to teamwork desk.
     *
     * @param $userId
     * @param $file
     *
     * @return string
     * @throws \DigitalEquation\Teamwork\Exceptions\TeamworkHttpException
     * @throws \DigitalEquation\Teamwork\Exceptions\TeamworkUploadException
     */
    public function upload($userId, $file): string
    {
        if (empty($file)) {
            throw new TeamworkUploadException('No file provided.', 400);
        }

        $filename  = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        $path      = sys_get_temp_dir();
        $temp      = $file->move($path, $filename);
        $stream    = fopen($temp->getPathName(), 'r');

        try {
            /** @var Response $response */
            $response = $this->client->post('upload/attachment', [
                'multipart' => [
                    [
                        'name'     => 'file',
                        'contents' => $stream,
                    ], [
                        'name'     => 'userId',
                        'contents' => $userId,
                    ],
                ],
            ]);
            /** @var Stream $body */
            $body = $response->getBody();
            $body = json_decode($body->getContents(), true);

            if (!empty($stream)) {
                File::delete($temp->getPathName());
            }

            return json_encode([
                'id'   => $body['attachment']['id'],
                'file' => [
                    'id'        => $body['attachment']['id'],
                    'url'       => $body['attachment']['downloadURL'],
                    'extension' => $extension,
                    'name'      => $body['attachment']['filename'],
                    'size'      => $body['attachment']['size'],
                ],
            ]);
        } catch (ClientException $e) {
            throw new TeamworkHttpException($e->getMessage(), 400);
        }
    }
}