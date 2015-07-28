<?php

/**
 * Companies House Create Alert Command Handler Test
 *
 * @author Dan Eggleston <dan@stolenegg.com>
 */
namespace Dvsa\OlcsTest\Api\Domain\CommandHandler\CompaniesHouse;

use Dvsa\Olcs\Api\Domain\Command\CompaniesHouse\CreateAlert as Cmd;
use Dvsa\Olcs\Api\Domain\Command\Result;
use Dvsa\Olcs\Api\Domain\CommandHandler\CompaniesHouse\CreateAlert;
use Dvsa\Olcs\Api\Domain\Exception\NotFoundException;
use Dvsa\Olcs\Api\Domain\Repository\CompaniesHouseCompany as CompanyRepo;
use Dvsa\Olcs\Api\Domain\Repository\Organisation as OrganisationRepo;
use Dvsa\Olcs\Api\Entity\CompaniesHouse\CompaniesHouseAlert as AlertEntity;
use Dvsa\Olcs\Api\Entity\Organisation\Organisation as OrganisationEntity;
use Dvsa\OlcsTest\Api\Domain\CommandHandler\CommandHandlerTestCase;
use Mockery as m;

/**
 *  Companies House Create Alert Command Handler Test
 *
 * @author Dan Eggleston <dan@stolenegg.com>
 */
class CreateAlertTest extends CommandHandlerTestCase
{
    public function setUp()
    {
        $this->sut = new CreateAlert();
        $this->mockRepo('CompaniesHouseAlert', CompanyRepo::class);
        $this->mockRepo('Organisation', OrganisationRepo::class);

        $this->refData = [
            'reason_foo',
            'reason_bar',
        ];

        parent::setUp();
    }

    /**
     * Test handleCommand method
     */
    public function testHandleCommand()
    {
        $companyNumber = '01234567';

        $organisation = m::mock(OrganisationEntity::class)->makePartial();

        // expectations
        $this->repoMap['Organisation']
            ->shouldReceive('getByCompanyOrLlpNo')
            ->once()
            ->with($companyNumber)
            ->andReturn([$organisation]);

        /** @var AlertEntity $alert */
        $alert = null;
        $this->repoMap['CompaniesHouseAlert']
            ->shouldReceive('save')
            ->once()
            ->with(m::type(AlertEntity::class))
            ->andReturnUsing(
                function (AlertEntity $a) use (&$alert) {
                    $alert = $a;
                    $a->setId(69);
                }
            );

        // invoke
        $command = Cmd::create(
            [
                'companyNumber' => $companyNumber,
                'reasons' => ['reason_foo', 'reason_bar'],
            ]
        );
        $result = $this->sut->handleCommand($command);

        // assertions
        $this->assertInstanceOf(Result::class, $result);
        $this->assertEquals(['companiesHouseAlert' => 69], $result->getIds());
        $this->assertEquals(['Alert created: ["reason_foo","reason_bar"]'], $result->getMessages());

        $this->assertEquals($companyNumber, $alert->getCompanyOrLlpNo());
        $this->assertEquals(2, $alert->getReasons()->count());
        $this->assertEquals('reason_foo', $alert->getReasons()[0]->getReasonType()->getId());
        $this->assertEquals('reason_bar', $alert->getReasons()[1]->getReasonType()->getId());
    }

    public function testHandleCommandNoOrganisation()
    {
        $companyNumber = '01234567';

        // expectations
        $this->repoMap['Organisation']
            ->shouldReceive('getByCompanyOrLlpNo')
            ->once()
            ->with($companyNumber)
            ->andThrow(new NotFoundException('organisation not found'));

        // invoke
        $command = Cmd::create(
            [
                'companyNumber' => $companyNumber,
                'reasons' => ['reason_foo', 'reason_bar'],
            ]
        );
        $result = $this->sut->handleCommand($command);

        // assertions
        $this->assertInstanceOf(Result::class, $result);
        $this->assertEquals(
            ['Organisation not found for company 01234567, no alert created'],
            $result->getMessages()
        );
    }
}
