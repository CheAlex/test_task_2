<?php

namespace BlogModule\Controller;

use BlogModule\Service\ContentProvider\Content;
use BlogModule\Service\ContentProvider\ContentInterface;
use BlogModule\Service\ContentProvider\ContentProviderInterface;
use Phalcon\Http\Response;
use Phalcon\Http\ResponseInterface;
use Phalcon\Mvc\Controller as PhController;

class IndexController extends PhController
{
    /**
     * @return ResponseInterface
     */
    public function indexAction()
    {
        // remove first slash
        $uri  = substr($this->request->getURI(), 1);
        $host = $this->request->getServerName();

        /** @var ContentProviderInterface $contentProvider */
        $contentProvider = $this->getDI()->get('blog.content_provider');
        $content = $contentProvider->get($host, $uri);

        if (ContentInterface::CODE_OK !== $content->getCode()) {
            return new Response($content->getError(), $content->getCode());
        }

        return new Response($content->getContent(), $content->getCode());
    }
}
