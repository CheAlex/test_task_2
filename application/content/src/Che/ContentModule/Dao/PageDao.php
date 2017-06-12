<?php

namespace Che\ContentModule\Dao;

use Phalcon\Mvc\Model\ManagerInterface;
use Che\ContentModule\Entity\Page;

class PageDao implements PageDaoInterface
{
    /**
     * @var ManagerInterface
     */
    protected $modelsManager;

    /**
     * PageService constructor.
     * @param ManagerInterface $modelsManager
     */
    public function __construct(ManagerInterface $modelsManager)
    {
        $this->modelsManager = $modelsManager;
    }

    /**
     * {@inheritdoc}
     */
    public function getByHostAndUri(string $host, string $uri): ?Page
    {
        $phql = sprintf(
            'SELECT * FROM %s WHERE host = :host: AND uri = :uri: LIMIT 1',
            Page::class
        );

        /** @var \Phalcon\Mvc\Model\ResultsetInterface $resultSet */
        $resultSet = $this->modelsManager->executeQuery(
            $phql,
            [
                'host' => $host,
                'uri'  => $uri,
            ]
        );

        /** @var Page $page */
        $page = $resultSet->getFirst();

        if (false === $page) {
            return null;
        }

        return $page;
    }
}
