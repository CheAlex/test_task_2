<?php

namespace Che\ContentModule\Controller;

use Phalcon\Http\Response;
use Phalcon\Mvc\Controller as PhController;
use JsonRPC\Server;

class ApiController extends PhController
{
    public function contentAction()
    {
        $server = new Server();
        $procedureHandler = $server->getProcedureHandler();

        $jsonRpcPageService = $this->di->get('che_content.service.json_rpc_page_service');

        $procedureHandler->withObject($jsonRpcPageService);

        $data = $server->execute();
        echo $data;

//        $host = $app->request->getQuery('host');
//        $uri  = $app->request->getQuery('uri');
//
//        /** @var PageService $pageService */
//        $pageService = $app->getDI()->get('page_service');
//        $r = $pageService->get($host, $uri);
//
//        $data = $pageService->get($host, $uri);

//        $response = new Response($data);
//        $response->setRawHeader('Content-Type: "application/json; charset=UTF-8"');
//        $response->setJsonContent(
//            $data
//        );

//        return $response;
    }
}
