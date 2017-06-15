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

        /** @var PageDaoInterface $pageDao */
        $pageDao = $this->getDI()->get('che_blog.dao.page_dao');
        $page = $pageDao->getByHostAndUri($host, $uri);

        if (PageInterface::CODE_OK !== $page->getCode()) {
            return new Response($page->getError(), $page->getCode());
        }

        return new Response($page->getHtml(), $page->getCode());
    }
}
