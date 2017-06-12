<?php

namespace BlogModule\Controller;

use BlogModule\Service\ContentProvider\CachedContentProvider;
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
        $contentProvider = $this->di->get('blog.content_provider.cached');
        $contentProvider->clearHostData($host);

        return new Response('good', 200);
    }
}
