<?php

/**
 * Document
 *
 * @author Rob Caiger <rob@clocal.co.uk>
 */
namespace Dvsa\Olcs\Api\Domain\Repository;

use Dvsa\Olcs\Api\Entity\Doc\Document as Entity;
use Dvsa\Olcs\Api\Entity\System\Category as CategoryEntity;

/**
 * Document
 *
 * @author Rob Caiger <rob@clocal.co.uk>
 */
class Document extends AbstractRepository
{
    protected $entity = Entity::class;

    public function fetchListForTm($id)
    {
        $qb = $this->createQueryBuilder();
        $qb->andWhere($qb->expr()->eq($this->alias . '.category', ':category'))
            ->andWhere($qb->expr()->eq($this->alias . '.subCategory', ':subCategory'))
            ->andWhere($qb->expr()->eq($this->alias . '.transportManager', ':transportManager'))
            ->setParameter('category', CategoryEntity::CATEGORY_TRANSPORT_MANAGER)
            ->setParameter('subCategory', CategoryEntity::DOC_SUB_CATEGORY_TRANSPORT_MANAGER_CPC_OR_EXEMPTION)
            ->setParameter('transportManager', $id)
            ->orderBy($this->alias . '.id', 'DESC');
        return $qb->getQuery()->execute();
    }
}
