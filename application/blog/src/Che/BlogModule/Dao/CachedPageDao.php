<?php

namespace Che\BlogModule\Dao;

use Che\BlogModule\Model\PageInterface;
use Phalcon\Cache\BackendInterface;

/**
 * Class CachedPageDao
 *
 * @package Che\BlogModule\Dao
 */
class CachedPageDao implements PageDaoInterface
{
    /**
     * Page DAO instance.
     *
     * @var PageDaoInterface
     */
    protected $contentDao;

    /**
     * Cache backend.
     *
     * @var BackendInterface
     */
    protected $cache;

    /**
     * CachedPageDao constructor.
     *
     * @param PageDaoInterface $contentDao
     * @param BackendInterface $cache
     */
    public function __construct(PageDaoInterface $contentDao, BackendInterface $cache)
    {
        $this->contentDao = $contentDao;
        $this->cache = $cache;
    }

    /**
     * {@inheritdoc}
     */
    public function getByHostAndUri(string $host, string $uri): PageInterface
    {
        $cacheKey = $host . '_' .  $uri;

        $content = $this->cache->get($cacheKey);

        if ($content === null) {
            $content = $this->contentDao->getByHostAndUri($host, $uri);

            $this->cache->save($cacheKey, $content);
        }

        return $content;
    }
}
