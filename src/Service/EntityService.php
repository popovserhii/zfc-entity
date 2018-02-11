<?php
namespace Popov\ZfcEntity\Service;

use Popov\ZfcEntity\Model\Entity;
use Popov\ZfcCore\Service\DomainServiceAbstract;

class EntityService extends DomainServiceAbstract
{
    protected $entity = Entity::class;
}