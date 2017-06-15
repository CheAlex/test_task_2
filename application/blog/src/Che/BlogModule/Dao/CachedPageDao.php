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
    protected $pageDao;

    /**
     * Cache backend.
     *
     * @var BackendInterface
     */
    protected $cache;

    /**
     * CachedPageDao constructor.
     *
     * @param PageDaoInterface $pageDao
     * @param BackendInterface $cache
     */
    public function __construct(PageDaoInterface $pageDao, BackendInterface $cache)
    {
        $this->pageDao = $pageDao;
        $this->cache = $cache;
    }

    /**
     * {@inheritdoc}
     */
    public function getByHostAndUri(string $host, string $uri): PageInterface
    {
        $cacheKey = $host . '_' .  $uri;

        $page = $this->cache->get($cacheKey);

        if ($page === null) {
            $page = $this->pageDao->getByHostAndUri($host, $uri);

            $this->cache->save($cacheKey, $page);
        }

        return $page;
    }
}
