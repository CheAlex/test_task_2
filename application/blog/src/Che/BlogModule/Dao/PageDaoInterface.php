<?php

namespace Che\BlogModule\Dao;

use Che\BlogModule\Model\PageInterface;

/**
 * Interface PageDaoInterface
 *
 * @package Che\BlogModule\Dao
 */
interface PageDaoInterface
{
    /**
     * @param string $host
     * @param string $uri
     * @return PageInterface
     */
    public function getByHostAndUri(string $host, string $uri): PageInterface;
}
