<?php

namespace Che\ContentModule\Dao;

use Che\ContentModule\Entity\Page;

/**
 * Interface PageDaoInterface
 *
 * @package Che\ContentModule\Dao
 */
interface PageDaoInterface
{
    /**
     * Get page by host an uri.
     *
     * @param string $host
     * @param string $uri
     * @return Page|null
     */
    public function getByHostAndUri(string $host, string $uri): ?Page;
}
