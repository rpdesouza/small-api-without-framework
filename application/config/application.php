<?php
return [
    'IoC'    => [
        'Application' => function () {
            $config = System\Config::getInstance( ENVIRONMENT );
            $application = new System\Application( $config );
            $application->setLogger( System\IoC::getInstance( 'Logger' ) );
            $application->setRouter( System\IoC::getInstance( 'Router' ) );

            return $application;
        },
        'Logger'      => function () {
            $logger = new System\Logger();
            $logger->setWriter( new System\Logger\Writer\File() );

            return $logger;
        },
        'Request'     => function () {
            $request = System\Request::getInstance( $_SERVER );

            return $request;
        },
        'Router'      => function () {
            $router = new System\Router( System\IoC::getInstance( 'Request' ), System\Config::getInstance( ENVIRONMENT ) );
            $router->setLogger( System\IoC::getInstance( 'Logger' ) );

            return $router;
        },
    ],
    'routes' => [
        'index' => [
            'GET'    => [
                'controller' => 'Application\Controller\Index',
                'action'     => 'Index'
            ],
            'DELETE'    => [
                'controller' => 'Application\Controller\Index',
                'action'     => 'Index'
            ],
            'PUT'    => [
                'controller' => 'Application\Controller\Index',
                'action'     => 'Index'
            ],
            'POST'    => [
                'controller' => 'Application\Controller\Index',
                'action'     => 'Index'
            ],
        ],
        'address' => [
            'GET'    => [
                'controller' => 'Application\Controller\Address',
                'action'     => 'AddressGet'
            ],
            'DELETE' => [
                'controller' => 'Application\Controller\Address',
                'action'     => 'AddressDelete'
            ],
            'PUT'    => [
                'controller' => 'Application\Controller\Address',
                'action'     => 'AddressPut'
            ],
            'POST'    => [
                'controller' => 'Application\Controller\Address',
                'action'     => 'AddressPost'
            ],
        ]
    ]

];