<?php

/**
 * Statement
 *
 * @author Rob Caiger <rob@clocal.co.uk>
 */
namespace Dvsa\Olcs\Api\Domain\Repository;

use Dvsa\Olcs\Api\Entity\Cases\Statement as Entity;
use Doctrine\ORM\Query;
use Dvsa\Olcs\Api\Domain\Exception;
use Zend\Stdlib\ArraySerializableInterface as QryCmd;
use Doctrine\ORM\QueryBuilder;
use Dvsa\Olcs\Transfer\Query\QueryInterface;

/**
 * Statement
 *
 * @author Rob Caiger <rob@clocal.co.uk>
 */
class Statement extends AbstractRepository
{
    protected $entity = Entity::class;

    /**
     * Fetch the default record by it's id
     *
     * @param Query|QryCmd $query
     * @param int $hydrateMode

     * @return mixed
     * @throws Exception\NotFoundException
     * @throws Exception\VersionConflictException
     */
    public function fetchUsingCaseId(QryCmd $query, $hydrateMode = Query::HYDRATE_OBJECT)
    {
        /* @var \Doctrine\Orm\QueryBuilder $qb*/
        $qb = $this->createQueryBuilder();

        $this->getQueryBuilder()->modifyQuery($qb)
            ->withRefData()
            ->with('case')
            ->withPersonContactDetails('requestorsContactDetails')
            ->with('createdBy')
            ->with('lastModifiedBy')
            ->byId($query->getId());

        $qb->andWhere($qb->expr()->eq($this->alias . '.case', ':byCase'))
            ->setParameter('byCase', $query->getCase());

        $result = $qb->getQuery()->getResult($hydrateMode);

        if (empty($result)) {
            throw new Exception\NotFoundException('Resource not found');
        }

        return $result[0];
    }

    /**
     *
     * @param QueryBuilder $qb
     * @param QueryInterface $query
     */
    protected function applyListFilters(QueryBuilder $qb, QueryInterface $query)
    {
        $qb->andWhere($qb->expr()->eq($this->alias . '.case', ':byCase'))
            ->setParameter('byCase', $query->getCase());
    }
}
