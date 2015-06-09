<?php

/**
 * Financial Standing Rate test
 *
 * @author Dan Eggleston <dan@stolenegg.com>
 */
namespace Dvsa\OlcsTest\Api\Domain\Repository;

use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\EntityRepository;
use Mockery as m;
use Dvsa\Olcs\Api\Domain\Repository\FinancialStandingRate as RateRepo;
use Dvsa\Olcs\Api\Entity\System\FinancialStandingRate;

/**
 * Financial Standing Rate test
 *
 * @author Dan Eggleston <dan@stolenegg.com>
 */
class FinancialStandingRateTest extends RepositoryTestCase
{
    public function setUp()
    {
        $this->setUpSut(RateRepo::class);
    }

    public function testGetRatesInEffect()
    {
        $date = m::mock('\DateTime');
        $date->shouldReceive('format')->andReturn('2015-06-04');

        /** @var QueryBuilder $qb */
        $qb = m::mock(QueryBuilder::class);
        $where1 = m::mock();
        $where2 = m::mock();

        $qb->shouldReceive('expr->isNull')
            ->with('fsr.deletedDate')
            ->andReturn($where1);

        $qb->shouldReceive('expr->lte')
            ->with('fsr.effectiveFrom', ':effectiveFrom')
            ->andReturn($where2);

        $qb
            ->shouldReceive('andWhere')
            ->with($where1)
            ->andReturnSelf()
            ->shouldReceive('andWhere')
            ->with($where2)
            ->andReturnSelf()
            ->shouldReceive('addOrderBy')
            ->with('fsr.effectiveFrom', 'DESC')
            ->andReturnSelf()
            ->shouldReceive('setParameter')
            ->with('effectiveFrom', '2015-06-04')
            ->shouldReceive('getQuery->execute')
            ->andReturn('RESULT');

        $this->queryBuilder
            ->shouldReceive('withRefdata')
            ->once()
            ->andReturnSelf();

        /** @var EntityRepository $repo */
        $repo = m::mock(EntityRepository::class);
        $repo->shouldReceive('createQueryBuilder')
            ->andReturn($qb);

        $this->em->shouldReceive('getRepository')
            ->with(FinancialStandingRate::class)
            ->andReturn($repo);

        $result = $this->sut->getRatesInEffect($date);

        $this->assertEquals('RESULT', $result);
    }
}
