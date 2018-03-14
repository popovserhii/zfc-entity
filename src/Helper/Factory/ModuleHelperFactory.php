<?php
/**
 * Module helper factory
 *
 * @category Agere
 * @package Agere_Module
 * @author Serhii Popov <popow.serhii@gmail.com>
 * @datetime: 04.02.15 10:30
 */

namespace Popov\ZfcEntity\Controller\Plugin\Factory;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Mvc\Controller\PluginManager;
use Popov\ZfcEntity\Controller\Plugin\ModuleHelper;
use Popov\ZfcEntity\Service\ModuleService;

class ModuleHelperFactory {

	public function __invoke(ServiceLocatorInterface $cpm) {
		/** @var PluginManager $cpm */
        $sm = $cpm->getServiceLocator();

		//$om = $sm->get('Doctrine\ORM\EntityManager');
		//$cpm = $sm->get('ControllerPluginManager');
		$vhm = $sm->get('ViewHelperManager');

		//$config = $sm->get('Config');
		$current = $cpm->get('current');

        $entity = $cpm->get('entity');
		//$formElement = $vhm->get('formElement');
		$translator = $vhm->get('translate');

		//$changer = $sm->get('StatusChanger');
        /** @var ModuleService $moduleService */
		$moduleService = $sm->get('ModuleService');

		return (new ModuleHelper($moduleService/*, $current*/))
			->injectCurrentPlugin($current)
			->injectEntityPlugin($entity)
			->injectTranslator($translator)
		;
	}

}