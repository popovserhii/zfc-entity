<?php
/**
 * Module Service
 *
 * @category Agere
 * @package Agere_Entity
 * @author Serhii Popov <popow.serhii@gmail.com>
 * @datetime: 21.07.2016 12:57
 */
namespace Popov\ZfcEntity\Service;

use Popov\ZfcCore\Service\DomainServiceAbstract;
use Popov\ZfcEntity\Model\Module;

class ModuleService extends DomainServiceAbstract
{
    protected $entity = Module::class;
}