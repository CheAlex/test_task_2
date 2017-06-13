<?php

namespace Che\BlogModule\Controller;

use Che\BlogModule\Service\CacheService;
use Phalcon\Http\Response;
use Phalcon\Http\ResponseInterface;
use Phalcon\Mvc\Controller as PhController;

/**
 * Class CacheController
 *
 * @package Che\BlogModule\Controller
 */
class CacheController extends PhController
{
    /**
     * @return ResponseInterface
     */
    public function clearAction()
    {
        $host = $this->request->getServerName();

        /** @var CacheService $cacheService */
        $cacheService = $this->di->get('che_blog.service.cache_service');
        $cacheService->clearCacheByHost($host);

        return new Response('OK', 200);
    }
}
