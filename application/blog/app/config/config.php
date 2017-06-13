<?php

return [
    'content' => [
        'json_rpc_server' => 'http://content.dev:80/api/v1/content'
    ],
    'memcached'     => [
        'back' => [
            'host'     => '172.28.1.2',
            'port'     => '11211',
            'weight'   => '1',
            'statsKey' => '_PHCM',
        ],
        'front' => [
            'lifetime' => 0,
        ]
    ],
    'logger'        => [
        'defaultFilename' => 'application',
        'format'          => '[%date%][%type%] %message%',
    ],
    'routes' => [
        [
            'class'   => Che\BlogModule\Controller\IndexController::class,
            'methods' => [
                'get' => [
                    '/{id:.+}' => 'indexAction',
                ],
            ],
        ],
        [
            'class'   => Che\BlogModule\Controller\CacheController::class,
            'methods' => [
                'get' => [
                    '/cache/clear' => 'clearAction',
                ],
            ],
        ],
    ],
];
