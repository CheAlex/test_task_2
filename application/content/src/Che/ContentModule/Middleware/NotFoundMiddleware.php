<?php

namespace Che\ContentModule\Middleware;

use Phalcon\Mvc\Micro;
use Phalcon\Mvc\User\Plugin;
use Phalcon\Mvc\Micro\MiddlewareInterface;

/**
 * Class NotFoundMiddleware.
 *
 * @package Che\ContentModule\Middleware
 */
class NotFoundMiddleware extends Plugin implements MiddlewareInterface
{
    /**
     * If the endpoint has not been found, show this.
     *
     * @return bool
     */
    public function beforeNotFound()
    {
        $this->response->setStatusCode(404);
        $this->response->setContent('Not found');
        $this->response->send();

        return false;
    }

    /**
     * Call me
     *
     * @param Micro $application
     *
     * @return bool
     */
    public function call(Micro $application)
    {
        return true;
    }
}
