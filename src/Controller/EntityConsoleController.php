<?php
/**
 * Created by PhpStorm.
 * User: Andrey
 * Date: 26.09.2018
 * Time: 10:56
 */

namespace Popov\ZfcEntity\Controller;

use Zend\Mvc\Console\Controller\AbstractConsoleController;
use Zend\Console\Adapter\AdapterInterface as Console;
use Popov\ZfcEntity\Helper\EntityHelper;
use Popov\ZfcEntity\Helper\ModuleHelper;
use Popov\ZfcEntity\Service\EntityService;

/**
 * @method EntityHelper entity($context = null)
 * @method ModuleHelper module($context = null)
 */
class EntityConsoleController extends AbstractConsoleController
{
    /**
     * @var Console
     */
    protected $console;

    /**
     * @var \Popov\ZfcEntity\Service\EntityService
     */
    protected $entityService;

    public function __construct(EntityService $entityService)
    {
        $this->entityService = $entityService;
    }

    public function syncAction()
    {
        $this->entityService->getData();
    }
}