<?php
namespace Popov\ZfcEntity;

return [
    'dependencies' => [
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
        'factories' => [
            Helper\EntityHelper::class => Helper\Factory\EntityHelperFactory::class,
            Helper\ModuleHelper::class => Helper\Factory\ModuleHelperFactory::class,
            //Helper\EntityServiceCreator::class => Helper\Factory\EntityServiceCreatorFactory::class,
        ],
    ],

    // mvc
	'controller_plugins' => [
		'factories' => [
			'entity' => Controller\Plugin\Factory\EntityPluginFactory::class,
			#'module' => Controller\Plugin\Factory\ModuleHelperFactory::class,
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
