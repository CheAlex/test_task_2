<?php

namespace Che\BlogModule\Service\ContentProvider;

use JsonRPC\Client;

/**
 * Class JsonRpcContentProvider
 *
 * @package App\Service\ContentProvider
 */
class JsonRpcContentProvider implements ContentProviderInterface
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * JsonRpcContentProvider constructor.
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * {@inheritdoc}
     */
    public function get(string $host, string $uri): ContentInterface
    {
        $data = $this->client->execute('getPage', [$host, $uri]);

        $response = new Content($data['code'], $data['html'], $data['error']);

        return $response;
    }
}
