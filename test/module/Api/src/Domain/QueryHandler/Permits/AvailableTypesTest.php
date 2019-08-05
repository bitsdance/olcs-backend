<?php

namespace Dvsa\OlcsTest\Api\Domain\QueryHandler\Permits;

use Dvsa\Olcs\Api\Domain\QueryHandler\Permits\AvailableTypes;
use Dvsa\Olcs\Api\Domain\Repository\IrhpPermitType as IrhpPermitTypeRepo;
use Dvsa\Olcs\Api\Entity\Permits\IrhpPermitType;
use Dvsa\Olcs\Api\Service\Permits\ShortTermEcmt\WindowAvailabilityChecker;
use Dvsa\Olcs\Transfer\Query\Permits\AvailableTypes as AvailableTypesQuery;
use Dvsa\OlcsTest\Api\Domain\QueryHandler\QueryHandlerTestCase;
use Mockery as m;
use DateTime;

class AvailableTypesTest extends QueryHandlerTestCase
{
    private $irhpPermitType1;

    private $irhpPermitType2;

    private $irhpPermitType3;

    private $irhpPermitTypes;

    public function setUp()
    {
        $this->sut = new AvailableTypes();
        $this->mockRepo('IrhpPermitType', IrhpPermitTypeRepo::class);

        $this->mockedSmServices = [
            'PermitsShortTermEcmtWindowAvailabilityChecker' => m::mock(WindowAvailabilityChecker::class)
        ];

        $this->irhpPermitType1 = m::mock(IrhpPermitType::class);
        $this->irhpPermitType1->shouldReceive('isEcmtShortTerm')
            ->andReturn(false);

        $this->irhpPermitType2 = m::mock(IrhpPermitType::class);
        $this->irhpPermitType2->shouldReceive('isEcmtShortTerm')
            ->andReturn(true);

        $this->irhpPermitType3 = m::mock(IrhpPermitType::class);
        $this->irhpPermitType3->shouldReceive('isEcmtShortTerm')
            ->andReturn(false);

        $this->irhpPermitTypes = [
            $this->irhpPermitType1,
            $this->irhpPermitType2,
            $this->irhpPermitType3
        ];

        $this->repoMap['IrhpPermitType']->shouldReceive('fetchAvailableTypes')
            ->with(m::type(DateTime::class))
            ->andReturn($this->irhpPermitTypes);

        parent::setUp();
    }

    public function testHandleQuery()
    {
        $query = AvailableTypesQuery::create([]);

        $this->mockedSmServices['PermitsShortTermEcmtWindowAvailabilityChecker']->shouldReceive('hasAvailability')
            ->with(m::type(DateTime::class))
            ->andReturn(true);

        $expectedIrhpPermitTypes = [
            $this->irhpPermitType1,
            $this->irhpPermitType2,
            $this->irhpPermitType3
        ];

        $this->assertEquals(
            ['types' => $expectedIrhpPermitTypes],
            $this->sut->handleQuery($query)
        );
    }

    public function testHandleQueryShortTermUnavailable()
    {
        $query = AvailableTypesQuery::create([]);

        $this->mockedSmServices['PermitsShortTermEcmtWindowAvailabilityChecker']->shouldReceive('hasAvailability')
            ->with(m::type(DateTime::class))
            ->andReturn(false);

        $expectedIrhpPermitTypes = [
            $this->irhpPermitType1,
            $this->irhpPermitType3
        ];

        $this->assertEquals(
            ['types' => $expectedIrhpPermitTypes],
            $this->sut->handleQuery($query)
        );
    }
}
