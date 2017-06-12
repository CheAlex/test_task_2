<?php

namespace Che\BlogModule\Service\ContentProvider;

use Phalcon\Cache\BackendInterface;

/**
 * Class CachedContentProvider
 *
 * @package BlogModule\Service\ContentProvider
 */
class CachedContentProvider implements ContentProviderInterface
{
    /**
     * @var ContentProviderInterface
     */
    protected $contentProvider;

    /**
     * @var BackendInterface
     */
    protected $cache;

    /**
     * CachedContentProvider constructor.
     * @param ContentProviderInterface $contentProvider
     * @param BackendInterface $cache
     */
    public function __construct(ContentProviderInterface $contentProvider, BackendInterface $cache)
    {
        $this->contentProvider = $contentProvider;
        $this->cache = $cache;
    }

    /**
     * {@inheritdoc}
     */
    public function get(string $host, string $uri): ContentInterface
    {
        $cacheKey = $host . '_' .  $uri;

        $content = $this->cache->get($cacheKey);

        if ($content === null) {
            $content = $this->contentProvider->get($host, $uri);

            $this->cache->save($cacheKey, $content);
        }

        return $content;
    }

    public function clearHostData(string $host)
    {
        $keys = $this->cache->queryKeys($host);

        foreach ($keys as $key) {
            $this->cache->delete($key);
        }
    }

//    public function deleteKeysByIndex($search)
//    {
//        $m = new \Memcached();
//        $m->addServer('localhost', 11211);
//        $keys = $m->getAllKeys();
//        foreach ($keys as $index => $key) {
//            if (strpos($key,$search) !== false) {
//                $m->delete($key);
//            } else {
//                unset($keys[$index]);
//            }
//        }
//
//        // returns an array of keys which were deleted
//        return $keys;
//    }
}
