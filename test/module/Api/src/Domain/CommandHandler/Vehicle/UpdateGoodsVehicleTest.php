<?php

/**
 * Update Goods Vehicle Test
 *
 * @author Rob Caiger <rob@clocal.co.uk>
 */
namespace Dvsa\OlcsTest\Api\Domain\CommandHandler\Vehicle;

use Doctrine\ORM\Query;
use Dvsa\Olcs\Api\Domain\Command\Result;
use Dvsa\Olcs\Api\Domain\Command\Vehicle\CeaseActiveDiscs;
use Dvsa\Olcs\Api\Domain\Command\Vehicle\CreateGoodsDiscs;
use Dvsa\Olcs\Api\Domain\Exception\BadRequestException;
use Dvsa\Olcs\Api\Domain\Exception\ForbiddenException;
use Dvsa\Olcs\Api\Entity\Licence\LicenceVehicle;
use Dvsa\Olcs\Api\Entity\User\Permission;
use Dvsa\Olcs\Api\Entity\Vehicle\Vehicle;
use Mockery as m;
use Dvsa\Olcs\Api\Domain\CommandHandler\Vehicle\UpdateGoodsVehicle;
use Dvsa\Olcs\Api\Domain\Repository\LicenceVehicle as LicenceVehicleRepo;
use Dvsa\Olcs\Transfer\Command\Vehicle\UpdateGoodsVehicle as Cmd;
use Dvsa\OlcsTest\Api\Domain\CommandHandler\CommandHandlerTestCase;
use ZfcRbac\Service\AuthorizationService;

/**
 * Update Goods Vehicle Test
 *
 * @author Rob Caiger <rob@clocal.co.uk>
 */
class UpdateGoodsVehicleTest extends CommandHandlerTestCase
{
    public function setUp()
    {
        $this->sut = new UpdateGoodsVehicle();
        $this->mockRepo('LicenceVehicle', LicenceVehicleRepo::class);

        $this->mockedSmServices[AuthorizationService::class] = m::mock(AuthorizationService::class);

        parent::setUp();
    }

    protected function initReferences()
    {
        $this->refData = [];

        $this->references = [];

        parent::initReferences();
    }

    public function testHandleCommandAttemptToUpdateRemovalDate()
    {
        $this->setExpectedException(ForbiddenException::class);

        $data = [
            'removalDate' => '2015-01-01'
        ];
        $command = Cmd::create($data);

        $this->mockedSmServices[AuthorizationService::class]->shouldReceive('isGranted')
            ->with(Permission::INTERNAL_USER, null)
            ->andReturn(false);

        $this->sut->handleCommand($command);
    }

    public function testHandleCommandAttemptToUpdateRemovalDateOnActiveRecord()
    {
        $this->setExpectedException(BadRequestException::class);

        $data = [
            'removalDate' => '2015-01-01',
            'version' => 1
        ];
        $command = Cmd::create($data);

        /** @var LicenceVehicle $licenceVehicle */
        $licenceVehicle = m::mock(LicenceVehicle::class)->makePartial();

        $this->mockedSmServices[AuthorizationService::class]->shouldReceive('isGranted')
            ->with(Permission::INTERNAL_USER, null)
            ->andReturn(true);

        $this->repoMap['LicenceVehicle']->shouldReceive('fetchUsingId')
            ->with($command, Query::HYDRATE_OBJECT, 1)
            ->andReturn($licenceVehicle);

        $this->sut->handleCommand($command);
    }

    public function testHandleCommandAttemptToUpdateRemovedRecord()
    {
        $this->setExpectedException(BadRequestException::class);

        $data = [
            'specifiedDate' => '2015-01-01',
            'version' => 1
        ];
        $command = Cmd::create($data);

        /** @var LicenceVehicle $licenceVehicle */
        $licenceVehicle = m::mock(LicenceVehicle::class)->makePartial();
        $licenceVehicle->setRemovalDate(new \DateTime());

        $this->mockedSmServices[AuthorizationService::class]->shouldReceive('isGranted')
            ->with(Permission::INTERNAL_USER, null)
            ->andReturn(true);

        $this->repoMap['LicenceVehicle']->shouldReceive('fetchUsingId')
            ->with($command, Query::HYDRATE_OBJECT, 1)
            ->andReturn($licenceVehicle);

        $this->sut->handleCommand($command);
    }

    public function testHandleCommand()
    {
        $data = [
            'platedWeight' => 100,
            'specifiedDate' => '2015-01-01',
            'receivedDate' => '2015-02-02',
            'version' => 1
        ];
        $command = Cmd::create($data);

        /** @var Vehicle $vehicle */
        $vehicle = m::mock(Vehicle::class)->makePartial();

        /** @var LicenceVehicle $licenceVehicle */
        $licenceVehicle = m::mock(LicenceVehicle::class)->makePartial();
        $licenceVehicle->setVehicle($vehicle);

        $this->mockedSmServices[AuthorizationService::class]->shouldReceive('isGranted')
            ->with(Permission::INTERNAL_USER, null)
            ->andReturn(true);

        $this->repoMap['LicenceVehicle']->shouldReceive('fetchUsingId')
            ->with($command, Query::HYDRATE_OBJECT, 1)
            ->andReturn($licenceVehicle)
            ->shouldReceive('save')
            ->with($licenceVehicle);

        $result = $this->sut->handleCommand($command);

        $expected = [
            'id' => [],
            'messages' => [
                'Vehicle updated'
            ]
        ];

        $this->assertEquals($expected, $result->toArray());

        $this->assertEquals('2015-01-01', $licenceVehicle->getSpecifiedDate()->format('Y-m-d'));
        $this->assertEquals('2015-02-02', $licenceVehicle->getReceivedDate()->format('Y-m-d'));
        $this->assertEquals(100, $vehicle->getPlatedWeight());
    }

    public function testHandleCommandUpdateRemoved()
    {
        $data = [
            'removalDate' => '2015-01-01',
            'version' => 1
        ];
        $command = Cmd::create($data);

        /** @var Vehicle $vehicle */
        $vehicle = m::mock(Vehicle::class)->makePartial();

        /** @var LicenceVehicle $licenceVehicle */
        $licenceVehicle = m::mock(LicenceVehicle::class)->makePartial();
        $licenceVehicle->setVehicle($vehicle);
        $licenceVehicle->setRemovalDate(new \DateTime());

        $this->mockedSmServices[AuthorizationService::class]->shouldReceive('isGranted')
            ->with(Permission::INTERNAL_USER, null)
            ->andReturn(true);

        $this->repoMap['LicenceVehicle']->shouldReceive('fetchUsingId')
            ->with($command, Query::HYDRATE_OBJECT, 1)
            ->andReturn($licenceVehicle)
            ->shouldReceive('save')
            ->with($licenceVehicle);

        $result = $this->sut->handleCommand($command);

        $expected = [
            'id' => [],
            'messages' => [
                'Vehicle updated'
            ]
        ];

        $this->assertEquals($expected, $result->toArray());

        $this->assertEquals('2015-01-01', $licenceVehicle->getRemovalDate()->format('Y-m-d'));
    }
}
