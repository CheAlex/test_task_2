<?php

namespace Store\Service;

use Phalcon\Db\AdapterInterface;
use Phalcon\Mvc\Model\ManagerInterface;
use Store\Dao\PageDao;

class PageService
{
    /**
     * @var PageDao
     */
    protected $pageDao;

    /**
     * PageService constructor.
     * @param PageDao $pageDao
     */
    public function __construct(PageDao $pageDao)
    {
        $this->pageDao = $pageDao;
    }

    public function get(string $host, string $uri)
    {
        $page = $this->pageDao->get($host, $uri);

        if (!$page) {
            return [
                'code'  => 404,
                'html'  => null,
                'error' => 'Page not found',
            ];
        }

        return [
            'code'  => 200,
            'html'  => $page->content,
            'error' => null,
        ];
    }
}
