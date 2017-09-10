<?php
namespace Popov\ZfcEntity\Controller\Plugin;

use Popov\ZfcCore\Service\DomainServiceInterface;
use Interop\Container\ContainerInterface;
use Zend\Filter\Word\CamelCaseToDash;
use Zend\Stdlib\Exception;
use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Zend\Filter\Word\DashToCamelCase;
use DoctrineModule\Persistence\ObjectManagerAwareInterface;
use DoctrineModule\Persistence\ProvidesObjectManager;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\Common\Persistence\Proxy;
use Doctrine\Common\Util\ClassUtils;
use Doctrine\ORM\UnitOfWork;
use Popov\ZfcCore\Service\ServiceManagerAwareTrait;
use Popov\ZfcCurrent\Plugin\Current;
use Popov\ZfcEntity\Model\Entity;
use Popov\ZfcEntity\Service\EntityService;

class EntityPlugin extends AbstractPlugin/* implements ObjectManagerAwareInterface*/
{
    use ProvidesObjectManager;
    use ServiceManagerAwareTrait;

    /** Create empty item if not found */
    const CREATE_EMPTY = true;

    /** @var Current */
    protected $currentPlugin;

    /** @var EntityService */
    protected $entityService;

    public function __construct(EntityService $entityService)
    {
        $this->entityService = $entityService;
    }

    public function getEntityService()
    {
        return $this->entityService;
    }

    public function injectCurrentPlugin(Current $current)
    {
        $this->currentPlugin = $current;

        return $this;
    }

    public function getCurrentPlugin()
    {
        if (!$this->currentPlugin) {
            $this->currentPlugin = $this->getController()->plugin('current');
            //$this->setContext($this->currentPlugin->currentModule());
        }
        return $this->currentPlugin;
    }

    /**
     * @param object $item
     * @return string
     */
    public function getRealDoctrineClass($item)
    {
        $className = is_object($item) ? get_class($item) : $item;

        /** @var ObjectManager $om */
        $om = $this->getObjectManager();
        /** @var \Doctrine\ORM\Mapping\ClassMetadata $class */
        $class = $om->getMetadataFactory()->getMetadataFor($className);

        if ($class->isInheritanceTypeSingleTable()) {
            $className = get_parent_class($className);
        } elseif ($item instanceof Proxy) {
            $className = ClassUtils::getClass($item);
        } else {
            $className = ClassUtils::getRealClass($className);
        }

        return $className;
    }

    /**
     * @param string|object $class
     *
     * @return boolean
     */
    public function isDoctrineObject($class)
    {
        if (is_object($class)) {
            $class = ClassUtils::getClass($class);
        }
        $om = $this->getObjectManager();

        return !$om->getMetadataFactory()->isTransient($class);
    }

    public function isManaged($item)
    {
        return (UnitOfWork::STATE_MANAGED === $this->getObjectManager()->getUnitOfWork()->getEntityState($item));
    }

    public function isRemoved($item)
    {
        return (UnitOfWork::STATE_REMOVED === $this->getObjectManager()->getUnitOfWork()->getEntityState($item));
    }

    public function isDetached($item)
    {
        return (UnitOfWork::STATE_DETACHED === $this->getObjectManager()->getUnitOfWork()->getEntityState($item));
    }

    public function isNew($item)
    {
        return (UnitOfWork::STATE_NEW === $this->getObjectManager()->getUnitOfWork()->getEntityState($item));
    }

    /**
     * Does item contain status object? If not then status is saved in related object.
     *
     * @param string|object $item
     * @param string $property
     * @return bool
     * @link https://github.com/borisguery/bgylibrary/blob/master/library/Bgy/Doctrine/EntitySerializer.php#L87
     */
    public function getMainObjectClassOld($item, $property) {
        static $depth = 0, $maxDepth = 1;

        $className = is_object($item) ? get_class($item) : $item;

        if (property_exists($item, $property)) {
            return $className;
        } elseif ($depth < $maxDepth) {
            /** @var ClassMetadata $metadata */
            $om = $this->getObjectManager();

            $metadata = $om->getClassMetadata($className);

            foreach ($metadata->associationMappings as $field => $mapping) {
                //if ($mapping['type'] === ClassMetadata::ONE_TO_ONE || ) {
                if (in_array($mapping['type'], [ClassMetadata::ONE_TO_ONE, ClassMetadata::MANY_TO_ONE])) {
                    $itemClass = $mapping['targetEntity'];
                    //$getter = 'get' . ucfirst($field);
                    //$itemWithStatus = $item->{$getter}() ?: new $targetEntity();

                    $depth++;
                    $assigned = $this->getMainObjectClass($itemClass, $property);
                    $depth--;

                    if ($assigned) {
                        //$setter = 'set' . ucfirst($field);
                        //$itemWithStatus->getId() ? : $item->{$setter}($itemWithStatus);

                        return $assigned;
                    }
                }
            }

            throw new Exception\RuntimeException(sprintf(
                'Cannot find main object in "%s" with property "%s" and related objects with relation OneToOne or ManyToOne',
                $className,
                $property
            ));
        }

        return false;
    }

    public function getMainObjectClass($item, $property)
    {
        $className = is_object($item) ? get_class($item) : $item;

        if (property_exists($item, $property)) {
            return $className;
        } elseif ($assigned = $this->getMainObjectClassProperty($className, $property)) {
            //$assigned = $this->getMainObjectClassProperty($className, $property);
            return $assigned['class'];
        } else {
            return false;
        }
    }

    public function getMainObjectClassProperty($item, $property)
    {
        static $depth = 0, $maxDepth = 1;

        $className = is_object($item) ? get_class($item) : $item;

        if ($depth < $maxDepth) {
            /** @var ClassMetadata $metadata */
            $om = $this->getObjectManager();

            $metadata = $om->getClassMetadata($className);

            foreach ($metadata->associationMappings as $field => $mapping) {
                //if ($mapping['type'] === ClassMetadata::ONE_TO_ONE || ) {
                if (in_array($mapping['type'], [ClassMetadata::ONE_TO_ONE, ClassMetadata::MANY_TO_ONE])) {
                    $itemClass = $mapping['targetEntity'];
                    //$getter = 'get' . ucfirst($field);
                    //$itemWithStatus = $item->{$getter}() ?: new $targetEntity();

                    $depth++;
                    $assigned = $this->getMainObjectClass($itemClass, $property);
                    $depth--;

                    if ($assigned) {
                        //$setter = 'set' . ucfirst($field);
                        //$itemWithStatus->getId() ? : $item->{$setter}($itemWithStatus);

                        return ['class' => $assigned, 'property' => $field];
                    }
                }
            }

            throw new Exception\RuntimeException(sprintf(
                'Cannot find main object in "%s" with related objects with relation OneToOne or ManyToOne',
                $className
            ));
        } else {
            return false;
        }
    }

    /**
     * Find item by id
     *
     * @param $id
     * @param $entity
     * @param $createEmpty
     * @return object
     */
    public function find($id, Entity $entity, $createEmpty = false)
    {
        /** @var ContainerInterface $sm */
        $sm = $this->getServiceManager();
        $entityMnemo = (new DashToCamelCase())->filter($entity->getMnemo());
        /** @var DomainServiceInterface $service */
        $service = $sm->get($entityMnemo . 'Service');
        $item = $service->find($id);

        if (null === $item && $createEmpty === self::CREATE_EMPTY) {
            $om = $service->getObjectManager();
            $item = $service->getObjectModel();
            $om->persist($item);
        }

        return $item;
    }

    /**
     * Find entity by params
     *
     * @param $value
     * @param string $field
     * @return Entity
     */
    public function getBy($value, $field = 'id')
    {
        $entity = $this->getEntityService()->getRepository()->findOneBy([$field => $value]);

        return $entity;
    }

    /**
     * Get Entity object
     *
     * @return Entity
     */
    public function getEntity()
    {
        static $cache = [];
        $context = $this->getContext();
        if (isset($cache[$context])) {
            return $cache[$context];
        }
        $entity = $this->getObjectManager()->getRepository(Entity::class)->findOneBy(['namespace' => $context]);

        return $cache[$context] = $entity;
    }

    /**
     * @param $context
     * @return $this
     */
    public function setContext($context)
    {
        //$this->context = $this->getCurrentPlugin()->currentModule($context);
        if ($this->isDoctrineObject($context)) {
            $context = $this->getRealDoctrineClass($context);
        }

        $this->context = $context;

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
            $mnemo = $this->getEntity()->getMnemo();
        } elseif (is_object($mnemo)) {
            $mnemo = $mnemo->getMnemo();
        }
        $alias = lcfirst((new DashToCamelCase())->filter($mnemo));

        return $alias;
    }

    /**
     * @param string|object $mnemo
     * @return string
     */
    public function decodeAlias($mnemo = null)
    {
        if (null === $mnemo) {
            $mnemo = $this->getEntity()->getMnemo();
        } elseif (is_object($mnemo)) {
            $mnemo = $mnemo->getMnemo();
        }
        $alias = lcfirst((new CamelCaseToDash())->filter($mnemo));

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
