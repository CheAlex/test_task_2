<?php

namespace Che\BlogModule\Controller;

use Che\BlogModule\Service\ContentProvider\CachedContentProvider;
use Phalcon\Http\Response;
use Phalcon\Http\ResponseInterface;
use Phalcon\Mvc\Controller as PhController;

class CacheController extends PhController
{
    /**
     * @return ResponseInterface
     */
    public function clearAction()
    {
        $host = $this->request->getServerName();

        /** @var CachedContentProvider $contentProvider */
        $contentProvider = $this->di->get('che_blog.content_provider.cached');
        $contentProvider->clearHostData($host);

        return new Response('OK', 200);
    }
}
