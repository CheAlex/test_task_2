<?php

namespace BlogModule\Service\ContentProvider;

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
        $data = $this->client->execute('content/get', [
            'host' => $host,
            'uri' => $uri,
        ]);

        if (!$data || !isset($data['html'])) {
            throw new \Exception('Wrong content data');
        }

        $response = new Content($data['code'], $data['html'], $data['error']);

        return $response;
    }
}
