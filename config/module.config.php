<?php
namespace Popov\ZfcEntity;

return [

	'controller_plugins' => [
        /*'invokables' => [
            'entity' => Controller\Plugin\EntityPlugin::class,
        ],*/
		'factories' => [
			'entity' => Controller\Plugin\Factory\EntityPluginFactory::class,
			'module' => Controller\Plugin\Factory\ModulePluginFactory::class,
		]
	],

	'view_helpers' => [
		'aliases' => [
			'module' => 'entity',
		],
		'factories' => [
			'entity' => View\Helper\Factory\ModuleFactory::class,
		],
	],

	'service_manager' => [
		'aliases' => [
            'Entity' => Model\Entity::class,
            'Module' => Model\Module::class,
            'EntityService' => Service\EntityService::class,
            'ModuleService' => Service\ModuleService::class,
		],
        'invokables' => [
            Service\EntityService::class => Service\EntityService::class,
            Service\ModuleService::class => Service\ModuleService::class,
        ],
		#'factories' => [
		#	'Popov\Entity\Service\EntityService' => function ($sm) {
		#		$em = $sm->get('Doctrine\ORM\EntityManager');
		#		$service = \Popov\Agere\Service\Factory\Helper::create('entity/entity', $em);

		#		return $service;
		#	},
		#],
	],

    'doctrine' => [
        'driver' => [
            __NAMESPACE__ . '_driver' => [
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => [__DIR__ . '/../src/Model'],
            ],
            'orm_default' => [
                'drivers' => [
                    __NAMESPACE__ . '\Model' => __NAMESPACE__ . '_driver',
                ],
            ],
        ],
    ],
];
