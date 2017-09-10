<?php
namespace Popov\ZfcEntity\Service;

use Popov\ZfcEntity\Model\Entity as Module;
use Popov\ZfcCore\Service\DomainServiceAbstract;

class EntityService extends DomainServiceAbstract
{
    protected $entity = Module::class;
}