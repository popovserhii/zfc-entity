<?php

namespace Popov\ZfcEntity\Service;

use Popov\Simpler\SimplerHelper;
use Popov\ZfcEntity\Model\Entity;
use Popov\ZfcCore\Service\DomainServiceAbstract;
use Popov\ZfcEntity\Model\Module;

class EntityService extends DomainServiceAbstract
{
    protected $entity = Entity::class;

    protected $simplerHelper;

    public function __construct(SimplerHelper $simplerHelper)
    {
        $this->simplerHelper = $simplerHelper;
    }

    public function syncData()
    {
        $om = $this->getObjectManager();
        $namespaces = array();
        $metas = $this->getObjectManager()->getMetadataFactory()->getAllMetadata();

        foreach ($metas as $meta) {
            $namespaces[] = $meta->getName();
        }

        $entities = $this->getRepository()->findAll();
        $entities = $this->simplerHelper->setContext($entities)->asAssociate('namespace');

        foreach ($namespaces as $namespace) {
            $entity = $entities[$namespace] ?? null;

            if (!$entity) {
                $refl = new \ReflectionClass($namespace);
                $consts = $refl->getConstants();
                if ($consts && defined($namespace . '::MNEMO')) {
                    $modelMnemo = $consts['MNEMO'];
                } else {
                    $modelMnemo = explode("\\", $namespace);
                    $modelMnemo = lcfirst(end($modelMnemo));
                }

                $className = explode("\\", $namespace);

                $isModel = false;
                foreach ($className as $part) {
                    if ($part == 'Model') {
                        $isModel = true;
                    }
                }

                if ($isModel) {
                    $moduleName = substr($namespace, 0, strpos($namespace, '\Model'));
                } else {
                    $moduleName = substr($namespace, 0, strpos($namespace, '\Entity'));
                }

                $modules = $this->getObjectManager()->getRepository(Module::class)->findAll();
                $modules = $this->simplerHelper->setContext($modules)->asAssociate('name');

                $module = $modules[$moduleName] ?? null;

                if (!$module) {
                    $moduleMnemo = substr($moduleName, strpos($moduleName, '\\') + 1);

                    //$moduleMnemo = 'DoctrineModule';
                    //$moduleMnemo = 'DoctrineORMModule';
                    //$moduleMnemo = 'AsseticBundle';

                    $zfc = strpos($moduleMnemo, 'Zfc');
                    $moduleEnd = strpos($moduleMnemo, 'Module');
                    $bundleEnd = strpos($moduleMnemo, 'Bundle');

                    if ($zfc !== false) {
                        $moduleMnemo = substr($moduleMnemo, 3);
                    }

                    if ($moduleEnd !== false) {
                        $moduleMnemo = substr($moduleMnemo, 0, $moduleEnd);
                    }

                    if ($bundleEnd !== false) {
                        $moduleMnemo = substr($moduleMnemo, 0, $bundleEnd);
                    }

                    $moduleMnemo = str_replace('\\', '', lcfirst($moduleMnemo));

                    $newModule = new Module();
                    $newModule->setName($moduleName);
                    $newModule->setMnemo($moduleMnemo);

                    $om->persist($newModule);
                    $om->flush();

                    $module = $newModule;
                }

                $newEntity = new Entity();
                $newEntity->setNamespace($namespace);
                $newEntity->setMnemo($modelMnemo);
                $newEntity->setHidden(0);
                $newEntity->setModule($module);

                $om->persist($newEntity);
                $om->flush();
            }
        }
    }
}