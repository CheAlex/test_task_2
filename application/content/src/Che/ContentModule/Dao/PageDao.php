<?php

namespace Che\ContentModule\Dao;

use Che\ContentModule\Entity\Page;
use Phalcon\Mvc\Model\ManagerInterface;
use Phalcon\Mvc\Model\ResultsetInterface;

/**
 * Class PageDao.
 *
 * @package Che\ContentModule\Dao
 */
class PageDao implements PageDaoInterface
{
    /**
     * Models manager.
     *
     * @var ManagerInterface
     */
    protected $modelsManager;

    /**
     * PageDao constructor.
     *
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

        /** @var ResultsetInterface $resultSet */
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
