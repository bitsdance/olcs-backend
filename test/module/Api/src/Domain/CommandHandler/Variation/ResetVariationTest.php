<?php

/**
 * Reset Variation Test
 *
 * @author Jonathan Thomas <jonathan@opalise.co.uk>
 */
namespace Dvsa\OlcsTest\Api\Domain\CommandHandler\Variation;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query;
use Dvsa\Olcs\Api\Domain\Command\Result;
use Dvsa\Olcs\Api\Domain\Command\Variation\ResetVariation as Cmd;
use Dvsa\Olcs\Api\Domain\CommandHandler\Variation\ResetVariation;
use Dvsa\Olcs\Api\Domain\Exception\RequiresConfirmationException;
use Dvsa\Olcs\Api\Domain\Repository\Application;
use Dvsa\Olcs\Api\Entity\Application\Application as ApplicationEntity;
use Dvsa\Olcs\Api\Entity\Licence\Licence as LicenceEntity;
use Dvsa\Olcs\Api\Entity\System\RefData;
use Dvsa\Olcs\Api\Entity\Task\Task as TaskEntity;
use Dvsa\Olcs\Transfer\Command\Licence\CreateVariation;
use Dvsa\OlcsTest\Api\Domain\CommandHandler\CommandHandlerTestCase;
use Mockery as m;

/**
 * Reset Variation Test
 *
 * @author Jonathan Thomas <jonathan@opalise.co.uk>
 */
class ResetVariationTest extends CommandHandlerTestCase
{
    public function setUp(): void
    {
        $this->sut = new ResetVariation();
        $this->mockRepo('Application', Application::class);

        parent::setUp();
    }

    protected function initReferences()
    {
        $this->refData = [
            ApplicationEntity::APPLIED_VIA_POST,
        ];

        parent::initReferences();
    }

    public function testHandleCommand()
    {
        $applicationId = 57;
        $licenceId = 43;
        $receivedDate = '2022-03-01';
        $appliedVia = ApplicationEntity::APPLIED_VIA_POST;

        $application = m::mock(ApplicationEntity::class);
        $application->shouldReceive('getLicence->getId')
            ->withNoArgs()
            ->andReturn($licenceId);
        $application->shouldReceive('getReceivedDate')
            ->withNoArgs()
            ->andReturn($receivedDate);
        $application->shouldReceive('getAppliedVia')
            ->withNoArgs()
            ->andReturn($this->refData[$appliedVia]);

        $data = [
            'id' => $applicationId,
            'confirm' => true
        ];

        $command = Cmd::create($data);

        $this->repoMap['Application']->shouldReceive('fetchUsingId')
            ->with($command, Query::HYDRATE_OBJECT)
            ->once()
            ->globally()
            ->ordered()
            ->andReturn($application);

        $task1 = m::mock(TaskEntity::class)->makePartial();
        $task1->setIsClosed('N');
        $task1->shouldReceive('setIsClosed')
            ->with('Y')
            ->once()
            ->globally()
            ->ordered();

        $task2 = m::mock(TaskEntity::class)->makePartial();
        $task2->setIsClosed('Y');

        $tasks = new ArrayCollection([$task1, $task2]);
        $application->shouldReceive('getTasks')
            ->withNoArgs()
            ->andReturn($tasks);

        $this->repoMap['Application']->shouldReceive('save')
            ->with($application)
            ->once()
            ->globally()
            ->ordered();
        $this->repoMap['Application']->shouldReceive('delete')
            ->with($application)
            ->once()
            ->globally()
            ->ordered();

        $result = new Result();

        $this->expectedSideEffect(
            CreateVariation::class,
            [
                'id' => $licenceId,
                'appliedVia' => $appliedVia,
                'receivedDate' => $receivedDate
            ],
            $result
        );

        $result = $this->sut->handleCommand($command);

        $expectedMessages = [
            '1 task(s) closed',
            'Variation removed'
        ];

        $this->assertEquals(
            $expectedMessages,
            $result->getMessages()
        );
    }

    public function testHandleCommandRequireConfirmation()
    {
        $this->expectException(RequiresConfirmationException::class);

        $applicationId = 62;
        $application = m::mock(ApplicationEntity::class);

        $data = [
            'id' => $applicationId,
            'confirm' => false
        ];

        $command = Cmd::create($data);

        $this->repoMap['Application']->shouldReceive('fetchUsingId')
            ->with($command, Query::HYDRATE_OBJECT)
            ->andReturn($application);

        $this->sut->handleCommand($command);
    }
}
