<?php

namespace DigitalEquation\Teamwork\Services;

use DigitalEquation\Teamwork\Exceptions\TeamworkHttpException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Pool as GuzzlePool;
use GuzzleHttp\Psr7\Request as GuzzleRequest;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Stream;

class HelpDocs
{
    /**
     * @var \GuzzleHttp\Client
     */
    private Client $client;

    /**
     * HelpDocs constructor.
     *
     * @param \GuzzleHttp\Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Get HelpDocs sites.
     *
     * @return array
     * @throws \DigitalEquation\Teamwork\Exceptions\TeamworkHttpException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \JsonException
     */
    public function getSites(): array
    {
        try {
            /** @var Response $response */
            $response = $this->client->get('helpdocs/sites.json');
            /** @var Stream $body */
            $body = $response->getBody();

            return json_decode($body->getContents(), true, 512, JSON_THROW_ON_ERROR);
        } catch (ClientException $e) {
            throw new TeamworkHttpException($e->getMessage(), 400);
        }
    }

    /**
     * Get HelpDocs site.
     *
     * @param int $siteID
     *
     * @return array
     * @throws \DigitalEquation\Teamwork\Exceptions\TeamworkHttpException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \JsonException
     */
    public function getSite(int $siteID): array
    {
        try {
            /** @var Response $response */
            $response = $this->client->get(sprintf('helpdocs/sites/%s.json', $siteID));
            /** @var Stream $body */
            $body = $response->getBody();

            return json_decode($body->getContents(), true, 512, JSON_THROW_ON_ERROR);
        } catch (ClientException $e) {
            throw new TeamworkHttpException($e->getMessage(), 400);
        }
    }

    /**
     * Get articles within a category.
     *
     * @param int $categoryID
     * @param int $page
     *
     * @return array
     * @throws \DigitalEquation\Teamwork\Exceptions\TeamworkHttpException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \JsonException
     */
    public function getCategoryArticles($categoryID, $page = 1): array
    {
        try {
            /** @var Response $response */
            $response = $this->client->get(sprintf('helpdocs/categories/%s/articles.json', $categoryID), [
                'query' => compact('page'),
            ]);
            /** @var Stream $body */
            $body = $response->getBody();

            return json_decode($body->getContents(), true, 512, JSON_THROW_ON_ERROR);
        } catch (ClientException $e) {
            throw new TeamworkHttpException($e->getMessage(), 400);
        }
    }

    /**
     * Get articles within a site.
     *
     * @param int $siteID
     * @param int $page
     *
     * @return array
     * @throws \DigitalEquation\Teamwork\Exceptions\TeamworkHttpException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \JsonException
     */
    public function getSiteArticles(int $siteID, $page = 1): array
    {
        try {
            /** @var Response $response */
            $response = $this->client->get(sprintf('helpdocs/sites/%s/articles.json', $siteID), [
                'query' => compact('page'),
            ]);
            /** @var Stream $body */
            $body = $response->getBody();

            return json_decode($body->getContents(), true, 512, JSON_THROW_ON_ERROR);
        } catch (ClientException $e) {
            throw new TeamworkHttpException($e->getMessage(), 400);
        }
    }

    /**
     * Get article by id.
     *
     * @param int $articleID
     *
     * @return array
     * @throws \DigitalEquation\Teamwork\Exceptions\TeamworkHttpException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \JsonException
     */
    public function getArticle(int $articleID): array
    {
        try {
            /** @var Response $response */
            $response = $this->client->get(sprintf('helpdocs/articles/%s.json', $articleID));
            /** @var Stream $body */
            $body = $response->getBody();

            return json_decode($body->getContents(), true, 512, JSON_THROW_ON_ERROR);
        } catch (ClientException $e) {
            throw new TeamworkHttpException($e->getMessage(), 400);
        }
    }

    /**
     * Get articles (in bulk).
     *
     * @param int[] $articleIDs
     *
     * @return array
     */
    public function getArticles(array $articleIDs): array
    {
        $articles = [];

        $requests = array_map(static function ($articleID) {
            return new GuzzleRequest('GET', sprintf('helpdocs/articles/%s.json', $articleID));
        }, $articleIDs);

        $pool = new GuzzlePool($this->client, $requests, [
            'concurrency' => 10,
            'fulfilled'   => function ($response) use (&$articles) {
                $response = json_decode($response->getBody(), true, 512, JSON_THROW_ON_ERROR);

                $articles[] = $response['article'];
            },
        ]);

        $promise = $pool->promise();
        $promise->wait();

        return $articles;
    }

    /**
     * Get categories within a site.
     *
     * @param int $siteID
     *
     * @return array
     * @throws \DigitalEquation\Teamwork\Exceptions\TeamworkHttpException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \JsonException
     */
    public function getSiteCategories(int $siteID): array
    {
        try {
            /** @var Response $response */
            $response = $this->client->get(sprintf('helpdocs/sites/%s/categories.json', $siteID));
            /** @var Stream $body */
            $body = $response->getBody();

            return json_decode($body->getContents(), true, 512, JSON_THROW_ON_ERROR);
        } catch (ClientException $e) {
            throw new TeamworkHttpException($e->getMessage(), 400);
        }
    }
}
