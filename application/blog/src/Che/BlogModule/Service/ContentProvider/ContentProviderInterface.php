<?php

namespace Che\BlogModule\Service\ContentProvider;

/**
 * Interface ContentProviderInterface
 *
 * @package App\Service\ContentProvider
 */
interface ContentProviderInterface
{
    /**
     * @param string $host
     * @param string $uri
     * @return ContentInterface
     */
    public function get(string $host, string $uri): ContentInterface;
}
