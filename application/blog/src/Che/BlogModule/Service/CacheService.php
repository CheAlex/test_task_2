<?php

namespace Che\BlogModule\Service;

use Phalcon\Cache\BackendInterface;

/**
 * Class CacheService
 *
 * @package Che\BlogModule\Service
 */
class CacheService
{
    /**
     * Cache backend.
     *
     * @var BackendInterface
     */
    protected $cache;

    /**
     * CacheService constructor.
     *
     * @param BackendInterface $cache
     */
    public function __construct(BackendInterface $cache)
    {
        $this->cache = $cache;
    }

    /**
     * Clear cache by host
     *
     * @param string $host
     * @return void
     */
    public function clearCacheByHost(string $host): void
    {
        $keys = $this->cache->queryKeys($host);

        foreach ($keys as $key) {
            $this->cache->delete($key);
        }
    }
}
