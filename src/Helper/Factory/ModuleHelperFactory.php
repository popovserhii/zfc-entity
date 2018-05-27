<?php
/**
 * Module helper factory
 *
 * @category Popov
 * @package Popov_ZfcEntity
 * @author Serhii Popov <popow.serhii@gmail.com>
 * @datetime: 04.02.15 10:30
 */

namespace Popov\ZfcEntity\Helper\Factory;

use Popov\ZfcCurrent\CurrentHelper;
use Popov\ZfcEntity\Helper\EntityHelper;
use Popov\ZfcEntity\Helper\ModuleHelper;
use Psr\Container\ContainerInterface;
use Popov\ZfcEntity\Service\ModuleService;

class ModuleHelperFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $moduleService = $container->get(ModuleService::class);
        $currentHelper = $container->get(CurrentHelper::class);
        $entityHelper = $container->get(EntityHelper::class);

        return (new ModuleHelper($moduleService, $entityHelper, $currentHelper));
    }
}