<?php

namespace Che\ContentModule\Dao;

use Che\ContentModule\Entity\Page;

interface PageDaoInterface
{
    /**
     * @param string $host
     * @param string $uri
     * @return Page|null
     */
    public function getByHostAndUri(string $host, string $uri): ?Page;
}
