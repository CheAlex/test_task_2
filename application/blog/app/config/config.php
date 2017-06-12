<?php

return [
    'logger'        => [
        'defaultFilename' => 'application',
        'format'          => '[%date%][%type%] %message%',
//        'level'           => '[%date%][%type%] %message%',
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
