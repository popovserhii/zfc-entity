<?php
namespace Popov\ZfcEntity\Helper;

use Zend\Stdlib\Exception;
use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Zend\Filter\Word\DashToCamelCase;
//use Doctrine\Common\Persistence\ObjectManager;
//use Doctrine\Common\Util\ClassUtils;
//use Doctrine\Common\Persistence\Proxy;
use Popov\ZfcEntity\Service\ModuleService;
use Popov\ZfcCurrent\CurrentHelper;
use Popov\ZfcEntity\Model\Module;

class ModuleHelper
{
    /** @var ModuleService */
    protected $moduleService;

    /** @var EntityHelper */
    protected $entityHelper;

    /** @var CurrentHelper */
    protected $currentHelper;

    protected $translator;

    /** @var mixed */
    protected $context;

    /**
     * ModuleHelper constructor.
     *
     * @param ModuleService $moduleService
     * @param EntityHelper $entityHelper
     * @param CurrentHelper $currentHelper
     */
    public function __construct(
        ModuleService $moduleService,
        EntityHelper $entityHelper,
        CurrentHelper $currentHelper = null
    )
    {
        $this->moduleService = $moduleService;
        $this->entityHelper = $entityHelper;
        $this->currentHelper = $currentHelper;
    }

    public function getModuleService()
    {
        return $this->moduleService;
    }

    #public function injectTranslator($translator)
    #{
    #    $this->translator = $translator;

    #    return $this;
    #}

    #public function injectCurrentPlugin(CurrentHelper $current)
    #{
    #    $this->currentHelper = $current;

     #   return $this;
    #}

    public function getEntityHelper()
    {
        #if (!$this->entityHelper) {
        #    $this->entityHelper = $this->getController()->plugin('entity');
        #}
        return $this->entityHelper;
    }

    public function getCurrentHelper()
    {
        #if (!$this->currentHelper) {
        #    $this->currentHelper = $this->getController()->plugin('current');
            //$this->setContext($this->getCurrent()->currentModule());
        #    $this->setContext($this->currentHelper->currentModule());
        #}
        return $this->currentHelper;
    }

    #public function injectEntityPlugin(EntityHelper $entityPlugin)
    #{
    #    $this->entityHelper = $entityPlugin;

    #    return $this;
    #}

    public function get($name = null)
    {
        $module = $this->getModule();
        if ($name === null) {
            return $module;
        }
        $method = 'get' . ucfirst($name);
        if (method_exists($module, $method)) {
            return $module->{$method}();
        }

        return false;
    }

    public function current()
    {
        if (!$this->currentHelper) {
            throw new Exception\RuntimeException(sprintf(
                '%s is not passed to constructor. ' .
                'Please, declared it in "dependencies" configuration or pass it manually from factory',
                CurrentHelper::class
            ));
        }

        static $current;

        $this->setContext($this->getCurrentHelper()->currentModule());
        if ($current) {
            return $current;
        }
        $current = $this->getModule();

        return $current;
    }

    /**
     * @return Module
     */
    public function getModule()
    {
        static $cache = [];

        $context = $this->getContext();
        if (isset($cache[$context])) {
            return $cache[$context];
        }
        $repository = $this->getModuleService()->getRepository();
        $module = $repository->findOneBy(['name' => $context]);

        return $cache[$context] = $module;
    }

    /**
     * @param $id
     * @param string $field
     * @return Module
     */
    public function getBy($id, $field = 'id')
    {
        $repository = $this->getModuleService()->getRepository();
        $module = $repository->findOneBy([$field => $id]);

        return $module;
    }

    public function setRealContext($item)
    {
        $entityHelper = $this->getEntityHelper();

        $className = is_object($item) ? get_class($item) : $item;

        if ($entityHelper->isDoctrineObject($item)) {
            $className = $entityHelper->getDoctrineClass($item);
        }

        //$moduleName = $this->getCurrentPlugin()->currentModule($className);

        return $this->setContext($className);
    }

    public function setContext($context)
    {
        $this->context = $this->getCurrentHelper()->currentModule($context);

        return $this;
    }

    public function getContext()
    {
        return $this->context;
    }

    /**
     * @param string|object $mnemo
     * @return string
     */
    public function toAlias($mnemo = null)
    {
        if (null === $mnemo) {
            $mnemo = $this->getModule()->getMnemo();
        }

        $alias = $this->getEntityHelper()->toAlias($mnemo);

        return $alias;
    }

    public function __invoke()
    {
        $context = null;
        if ($args = func_get_args()) {
            $context = $args[0];
        }

        return $this->setContext($context);
    }
}
