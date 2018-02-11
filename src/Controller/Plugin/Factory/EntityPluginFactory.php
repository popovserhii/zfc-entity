<?php
/**
 * Module helper factory
 *
 * @category Agere
 * @package Agere_Module
 * @author Popov Sergiy <popov@agere.com.ua>
 * @datetime: 04.02.15 10:30
 */

namespace Popov\ZfcEntity\Controller\Plugin\Factory;

use Zend\ServiceManager\ServiceLocatorInterface;
use Popov\ZfcEntity\Controller\Plugin\EntityHelper;

class EntityPluginFactory {

	public function __invoke(ServiceLocatorInterface $cpm) {
		$sm = $cpm->getServiceLocator();

		$om = $sm->get('Doctrine\ORM\EntityManager');
		//$cpm = $sm->get('ControllerPluginManager');
		//$vhm = $sm->get('ViewHelperManager');

		//$config = $sm->get('Config');
		//$current = $cpm->get('current');
		//$formElement = $vhm->get('formElement');
		//$translator = $vhm->get('translate');

		//$changer = $sm->get('StatusChanger');
		$entityService = $sm->get('EntityService');

		$entityPlugin = new EntityHelper($entityService/*, $current*/);
            //->injectCurrentPlugin($current)
        $entityPlugin->setObjectManager($om);
        $entityPlugin->setServiceManager($sm);

        return $entityPlugin;
	}

}