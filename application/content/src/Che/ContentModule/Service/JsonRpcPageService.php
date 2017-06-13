<?php

namespace Che\ContentModule\Service;

use Che\ContentModule\Dao\PageDaoInterface;

/**
 * Class JsonRpcPageService.
 *
 * @package Che\ContentModule\Service
 */
class JsonRpcPageService
{
    /**
     * Page dao.
     *
     * @var PageDaoInterface
     */
    protected $pageDao;

    /**
     * JsonRpcPageService constructor.
     *
     * @param PageDaoInterface $pageDao
     */
    public function __construct(PageDaoInterface $pageDao)
    {
        $this->pageDao = $pageDao;
    }

    /**
     * Get page data by host and uri.
     *
     * @param string $host
     * @param string $uri
     * @return array
     */
    public function getByHostAndUri(string $host, string $uri): array
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
            'html'  => $page->html,
            'error' => null,
        ];
    }
}
