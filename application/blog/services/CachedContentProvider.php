<?php

namespace Blog\Service;

use Phalcon\Cache\Frontend\Data as FrontData;
use Phalcon\Cache\Backend\Libmemcached as BackMemCached;
use Store\Toys\Pages;

class CachedContentProvider implements ContentProviderInterface
{
    /**
     * @var ContentProviderInterface
     */
    protected $contentProvider;

    /**
     * CachedContentProvider constructor.
     * @param ContentProviderInterface $contentProvider
     */
    public function __construct(ContentProviderInterface $contentProvider)
    {
        $this->contentProvider = $contentProvider;
    }

    public function get(string $host, string $uri): ContentResponse
    {
        $frontCache = new FrontData(
            [
                "lifetime" => 5,
            ]
        );

        $cache = new BackMemCached(
            $frontCache,
            [
                "servers" => [
                    [
                        "host"   => "memcached_host",
                        "port"   => "11211",
                        "weight" => "1",
                    ]
                ]
            ]
        );

        $cacheKey = $host . '_' .  $uri;

        // Пробуем получить закэшированные записи
        $robots = $cache->get($cacheKey);

        if ($robots === null) {
            $robots = $this->contentProvider->get($host, $uri);

            // Сохраняем их в кэше
            $cache->save($cacheKey, $robots);
        }

        return $robots;
    }
}
