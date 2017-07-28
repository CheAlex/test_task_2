<?php

namespace Che\ContentModule\Controller;

use Phalcon\Http\Response;
use Phalcon\Http\ResponseInterface;
use Phalcon\Mvc\Controller as PhController;
use JsonRPC\Server as JsonRpcServer;

/**
 * Class ApiController
 *
 * @package Che\ContentModule\Controller
 */
class ApiController extends PhController
{
    /**
     * @return ResponseInterface
     */
    public function contentAction()
    {
        $jsonRpcPageService = $this->di->get('che_content.service.json_rpc_page_service');

        $server = new JsonRpcServer();

        $server->getProcedureHandler()->withObject($jsonRpcPageService);

        $data = $server->execute();

        $response = new Response($data);

        return $response;
    }
}
