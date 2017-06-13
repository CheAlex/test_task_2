<?php

return [
    'db' => [
        'host'     => '172.28.1.1',
        'username' => 'root',
        'password' => 'root',
        'dbname'   => 'phalcon',
    ],
    'logger'        => [
        'defaultFilename' => 'application',
        'format'          => '[%date%][%type%] %message%',
    ],
    'routes' => [
        [
            'class'   => Che\ContentModule\Controller\ApiController::class,
            'methods' => [
                'post' => [
                    '/api/v1/content' => 'contentAction',
                ],
            ],
        ],
    ],
];
