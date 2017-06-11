<?php

namespace Store\Dao;

use Phalcon\Mvc\Model\ManagerInterface;
use Store\Toys\Pages;

class PageDao
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

    public function get(string $host, string $uri): ?Pages
    {
        $phql = 'SELECT * FROM Store\\Toys\\Pages WHERE host = :host: AND uri = :uri: LIMIT 1';
//        $phql = 'SELECT * FROM Store\\Toys\\Pages';

        /** @var \Phalcon\Mvc\Model\ResultsetInterface $resultset */
        $resultset = $this->modelsManager->executeQuery(
            $phql,
            [
                'host' => $host,
                'uri'  => $uri,
            ]
        );

        $robot = $resultset->getFirst();

        if (false === $robot) {
            return null;
        }

        return $robot;
    }
}
