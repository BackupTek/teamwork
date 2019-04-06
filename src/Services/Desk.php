<?php

namespace DigitalEquation\Teamwork\Services;

use DigitalEquation\Teamwork\Exceptions\TeamworkHttpException;
use DigitalEquation\Teamwork\Exceptions\TeamworkInboxException;
use DigitalEquation\Teamwork\Exceptions\TeamworkUploadException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Stream;
use Illuminate\Support\Facades\File;

class Desk
{
    /**
     * @var \GuzzleHttp\Client
     */
    private $client;

    /**
     * Desk constructor.
     *
     * @param \GuzzleHttp\Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Return an inbox by name.
     *
     * @param string $name
     *
     * @return string
     * @throws \DigitalEquation\Teamwork\Exceptions\TeamworkHttpException
     * @throws \DigitalEquation\Teamwork\Exceptions\TeamworkInboxException
     */
    public function inbox($name): string
    {
        try {
            /** @var Response $response */
            $response = $this->client->get('inboxes.json');
            /** @var Stream $body */
            $body    = $response->getBody();
            $inboxes = json_decode($body->getContents(), true);

            $inbox = collect($inboxes['inboxes'])->first(
                function ($inbox) use ($name) {
                    return $inbox['name'] === $name;
                }
            );

            if (!$inbox) {
                throw new TeamworkInboxException("No inbox found with the name: $name!", 400);
            }

            return json_encode($inbox);
        } catch (ClientException $e) {
            throw new TeamworkHttpException($e->getMessage(), 400);
        }
    }

    /**
     * Get teamwork desk inboxes.
     *
     * @return string
     * @throws \DigitalEquation\Teamwork\Exceptions\TeamworkHttpException
     */
    public function inboxes(): string
    {
        try {
            /** @var Response $response */
            $response = $this->client->get('inboxes.json');
            /** @var Stream $body */
            $body = $response->getBody();

            return $body->getContents();
        } catch (ClientException $e) {
            throw new TeamworkHttpException($e->getMessage(), 400);
        }
    }

    /**
     * Upload file to teamwork API
     *
     * @param int $userId
     * @param     $file
     *
     * @return int
     * @throws \DigitalEquation\Teamwork\Exceptions\TeamworkHttpException
     * @throws \DigitalEquation\Teamwork\Exceptions\TeamworkUploadException
     */
    public function upload($userId, $file): int
    {
        if (empty($file)) {
            throw new TeamworkUploadException('No file provided.');
        }

        $filename = $file->getClientOriginalName();
        $path     = sys_get_temp_dir();
        $temp     = $file->move($path, $filename);
        $stream   = fopen($temp->getPathName(), 'r');

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
            $body     = $response->getBody();
            $response = json_decode($body->getContents());

            if (!empty($stream)) {
                File::delete($temp->getPathName());
            }

            return $response->attachment->id;
        } catch (ClientException $e) {
            throw new TeamworkHttpException($e->getMessage(), 400);
        }
    }

    /**
     * Return the current client info.
     *
     * @return string
     * @throws \DigitalEquation\Teamwork\Exceptions\TeamworkHttpException
     */
    public function me(): string
    {
        try {
            /** @var Response $response */
            $response = $this->client->get('me.json');
            /** @var Stream $body */
            $body = $response->getBody();

            return $body->getContents();
        } catch (ClientException $e) {
            throw new TeamworkHttpException($e->getMessage(), 400);
        }
    }
}