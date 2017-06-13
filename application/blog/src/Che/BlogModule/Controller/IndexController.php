<?php

namespace Che\BlogModule\Controller;

use Che\BlogModule\Dao\PageDaoInterface;
use Che\BlogModule\Model\PageInterface;
use Phalcon\Http\Response;
use Phalcon\Http\ResponseInterface;
use Phalcon\Mvc\Controller as PhController;

/**
 * Class IndexController
 *
 * @package Che\BlogModule\Controller
 */
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

        /** @var PageDaoInterface $contentDao */
        $contentDao = $this->getDI()->get('che_blog.content_provider');
        $content = $contentDao->getByHostAndUri($host, $uri);

        if (PageInterface::CODE_OK !== $content->getCode()) {
            return new Response($content->getError(), $content->getCode());
        }

        return new Response($content->getHtml(), $content->getCode());
    }
}
