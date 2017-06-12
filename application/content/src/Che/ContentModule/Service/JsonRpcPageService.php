<?php

namespace Che\ContentModule\Service;

use Che\ContentModule\Dao\PageDaoInterface;

class JsonRpcPageService
{
    /**
     * @var PageDaoInterface
     */
    protected $pageDao;

    /**
     * JsonRpcPageService constructor.
     * @param PageDaoInterface $pageDao
     */
    public function __construct(PageDaoInterface $pageDao)
    {
        $this->pageDao = $pageDao;
    }

    public function getPage(string $host, string $uri)
    {
        $page = $this->pageDao->getByHostAndUri($host, $uri);

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
