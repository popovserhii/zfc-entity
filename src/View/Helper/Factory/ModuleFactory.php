<?php
/**
 * Module helper factory
 *
 * @category Agere
 * @package Agere_Module
 * @author Popov Sergiy <popov@agere.com.ua>
 * @datetime: 04.02.15 10:30
 */

namespace Popov\ZfcEntity\View\Helper\Factory;

use Zend\ServiceManager\ServiceLocatorInterface;

use Popov\ZfcEntity\View\Helper\ModuleHelper as ModuleHelper;

class ModuleFactory {

	public function __invoke(ServiceLocatorInterface $vhm) {
		$sm = $vhm->getServiceLocator();

		//$om = $sm->get('Doctrine\ORM\EntityManager');
		$cpm = $sm->get('ControllerPluginManager');


		$modulePlugin = $cpm->get('module');

		return new ModuleHelper($modulePlugin);
	}

}