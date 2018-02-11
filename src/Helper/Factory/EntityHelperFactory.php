<?php
/**
 * The MIT License (MIT)
 * Copyright (c) 2018 Serhii Popov
 * This source file is subject to The MIT License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/MIT
 *
 * @category Popov
 * @package Popov_ZfcEntity
 * @author Serhii Popov <popow.serhii@gmail.com>
 * @license https://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace Popov\ZfcEntity\Helper\Factory;

use Popov\ZfcEntity\Helper\EntityServiceFactory;
use Popov\ZfcEntity\Service\EntityService;
use Psr\Container\ContainerInterface;
use Popov\ZfcEntity\Helper\EntityHelper;

class EntityHelperFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $entityServiceFactory = new EntityServiceFactory($container);
        $om = $container->get('Doctrine\ORM\EntityManager');
        //$entityService = $container->get(EntityService::class);
        $entityHelper = new EntityHelper(/*$entityService,*/ $entityServiceFactory);
        $entityHelper->setObjectManager($om);

        return $entityHelper;
    }

}