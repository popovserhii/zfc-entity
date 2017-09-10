<?php
namespace Popov\ZfcEntity\Model\Repository;

#use Doctrine\ORM\Query\ResultSetMapping;
#use Popov\Agere\ORM\EntityRepository as EntityRepositoryORM;

use Doctrine\ORM\EntityRepository as EntityRepositoryOrm;

class EntityRepository extends EntityRepositoryORM {

	protected $_table = 'entity';
	protected $_alias = 'e';

}