<?php

/**
 * BusServiceType list
 *
 * @author Alex Peshkov <alex.peshkov@valtech.co.uk>
 */
namespace Dvsa\Olcs\Api\Domain\QueryHandler\Bus;

use Dvsa\Olcs\Api\Domain\QueryHandler\AbstractQueryHandler;
use Dvsa\Olcs\Transfer\Query\QueryInterface;
use Doctrine\ORM\Query;

/**
 * BusServiceType list
 *
 * @author Alex Peshkov <alex.peshkov@valtech.co.uk>
 */
final class BusServiceTypeList extends AbstractQueryHandler
{
    protected $repoServiceName = 'BusServiceType';

    public function handleQuery(QueryInterface $query)
    {
        return [
            'results' => $this->resultList(
                $this->getRepo()->fetchList($query, Query::HYDRATE_OBJECT)
            ),
            'count' => $this->getRepo()->fetchCount($query)
        ];
    }
}
