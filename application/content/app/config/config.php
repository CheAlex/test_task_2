<?php

return [
    'logger'        => [
        'defaultFilename' => 'application',
        'format'          => '[%date%][%type%] %message%',
//        'level'           => '[%date%][%type%] %message%',
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
