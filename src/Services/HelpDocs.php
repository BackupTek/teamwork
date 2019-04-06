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
    private $client;

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
     * @return string
     * @throws \DigitalEquation\Teamwork\Exceptions\TeamworkHttpException
     */
    public function getSites(): string
    {
        try {
            /** @var Response $response */
            $response = $this->client->get('helpdocs/sites.json');
            /** @var Stream $body */
            $body = $response->getBody();

            return $body->getContents();
        } catch (ClientException $e) {
            throw new TeamworkHttpException($e->getMessage(), 400);
        }
    }

    /**
     * Get Helpdocs site.
     *
     * @param integer $siteID
     *
     * @return string
     * @throws \DigitalEquation\Teamwork\Exceptions\TeamworkHttpException
     */
    public function getSite($siteID): string
    {
        try {
            /** @var Response $response */
            $response = $this->client->get(sprintf('helpdocs/sites/%s.json', $siteID));
            /** @var Stream $body */
            $body = $response->getBody();

            return $body->getContents();
        } catch (ClientException $e) {
            throw new TeamworkHttpException($e->getMessage(), 400);
        }
    }

    /**
     * Get articles within a category.
     *
     * @param integer $categoryID
     * @param integer $page
     *
     * @return string
     * @throws \DigitalEquation\Teamwork\Exceptions\TeamworkHttpException
     */
    public function getCategoryArticles($categoryID, $page = 1): string
    {
        try {
            /** @var Response $response */
            $response = $this->client->get(sprintf('helpdocs/categories/%s/articles.json', $categoryID), [
                'query' => compact('page'),
            ]);
            /** @var Stream $body */
            $body = $response->getBody();

            return $body->getContents();
        } catch (ClientException $e) {
            throw new TeamworkHttpException($e->getMessage(), 400);
        }
    }

    /**
     * Get articles within a site.
     *
     * @param integer $siteID
     * @param integer $page
     *
     * @return string
     * @throws \DigitalEquation\Teamwork\Exceptions\TeamworkHttpException
     */
    public function getSiteArticles($siteID, $page = 1): string
    {
        try {
            /** @var Response $response */
            $response = $this->client->get(sprintf('helpdocs/sites/%s/articles.json', $siteID), [
                'query' => compact('page'),
            ]);
            /** @var Stream $body */
            $body = $response->getBody();

            return $body->getContents();
        } catch (ClientException $e) {
            throw new TeamworkHttpException($e->getMessage(), 400);
        }
    }

    /**
     * Get article by id.
     *
     * @param integer $articleID
     *
     * @return string
     * @throws \DigitalEquation\Teamwork\Exceptions\TeamworkHttpException
     */
    public function getArticle($articleID): string
    {
        try {
            /** @var Response $response */
            $response = $this->client->get(sprintf('helpdocs/articles/%s.json', $articleID));
            /** @var Stream $body */
            $body = $response->getBody();

            return $body->getContents();
        } catch (ClientException $e) {
            throw new TeamworkHttpException($e->getMessage(), 400);
        }
    }

    /**
     * Get articles (in bulk)
     *
     * @param $articleIDs
     *
     * @return string
     */
    public function getArticles($articleIDs): string
    {
        $articles = [];

        $requests = array_map(function ($articleID) {
            return new GuzzleRequest('GET', sprintf('helpdocs/articles/%s.json', $articleID));
        }, $articleIDs);

        $pool = new GuzzlePool($this->client, $requests, [
            'concurrency' => 10,
            'fulfilled'   => function ($response) use (&$articles) {
                $response = json_decode($response->getBody(), true);

                $articles[] = $response['article'];
            },
        ]);

        $promise = $pool->promise();
        $promise->wait();

        return json_encode($articles);
    }

    /**
     * Get categories within a site.
     *
     * @param integer $siteID
     *
     * @return string
     * @throws \DigitalEquation\Teamwork\Exceptions\TeamworkHttpException
     */
    public function getSiteCategories($siteID): string
    {
        try {
            /** @var Response $response */
            $response = $this->client->get(sprintf('helpdocs/sites/%s/categories.json', $siteID));
            /** @var Stream $body */
            $body = $response->getBody();

            return $body->getContents();
        } catch (ClientException $e) {
            throw new TeamworkHttpException($e->getMessage(), 400);
        }
    }
}
