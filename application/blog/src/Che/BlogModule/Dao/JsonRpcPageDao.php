<?php

namespace Che\BlogModule\Dao;

use Che\BlogModule\Model\Page;
use Che\BlogModule\Model\PageInterface;
use JsonRPC\Client as JsonRpcClient;

/**
 * Class JsonRpcPageDao
 *
 * @package Che\BlogModule\Dao
 */
class JsonRpcPageDao implements PageDaoInterface
{
    /**
     * Json-rpc client/
     *
     * @var JsonRpcClient
     */
    protected $client;

    /**
     * JsonRpcPageDao constructor.
     *
     * @param JsonRpcClient $client
     */
    public function __construct(JsonRpcClient $client)
    {
        $this->client = $client;
    }

    /**
     * {@inheritdoc}
     */
    public function getByHostAndUri(string $host, string $uri): PageInterface
    {
        $data = $this->client->execute('getByHostAndUri', [$host, $uri]);

        $response = new Page($data['code'], $data['html'], $data['error']);

        return $response;
    }
}
