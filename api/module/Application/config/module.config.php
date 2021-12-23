<?php

namespace Application;

use Laminas\Router\Http\Literal;
use Laminas\ServiceManager\Factory\InvokableFactory;

return [
    'router' => [
        'routes' => [
            'home' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'isAuthorizationRequired' => false,
                        'methodsAuthorization'    => ['GET'],
                        'action'     => 'index',
                    ],
                ],
            ],
            'ping' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/ping',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'isAuthorizationRequired' => false,
                        'methodsAuthorization'    => ['GET'],
                        'action'     => 'ping',
                    ],
                ],
            ],
            'post-ping' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/post-ping',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'isAuthorizationRequired' => false,
                        'methodsAuthorization'    => ['POST'],
                        'action'     => 'postPing',
                    ],
                ],
            ],
            'timer' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/timer',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'isAuthorizationRequired' => false,
                        'methodsAuthorization'    => ['GET'],
                        'action'     => 'timer',
                    ],
                ],
            ],
            'loop' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/loop',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'isAuthorizationRequired' => false,
                        'methodsAuthorization'    => ['GET'],
                        'action'     => 'loop',
                    ],
                ],
            ],
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\IndexController::class => InvokableFactory::class
        ],
    ],
    'view_manager' => [
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => [
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
        'strategies' => [
            'ViewJsonStrategy',
        ]
    ],
    'doctrine' => [
        'driver' => [
            __NAMESPACE__ . '_driver' => [
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => [__DIR__ . '/../src/Entity']
            ],

            'orm_default' => [
                'drivers' => [
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
                ]
            ]
        ],
    ],
];
