<?php

namespace Dvsa\OlcsTest\Api\Domain\CommandHandler\Permits;

use Dvsa\Olcs\Api\Entity\Permits\EcmtPermitApplication;
use Dvsa\Olcs\Api\Entity\Permits\IrhpPermitType;
use Dvsa\Olcs\Api\Entity\Queue\Queue;
use Dvsa\Olcs\Api\Entity\Task\Task;
use Dvsa\Olcs\Api\Domain\Command\Result;
use Dvsa\Olcs\Api\Domain\Command\Task\CreateTask;
use Dvsa\Olcs\Api\Domain\CommandHandler\Permits\EcmtSubmitApplication;
use Dvsa\Olcs\Api\Service\Permits\Checkable\CreateTaskCommandGenerator;
use Dvsa\Olcs\Transfer\Command\CommandInterface;
use Dvsa\OlcsTest\Api\Domain\CommandHandler\CommandHandlerTestCase;
use Mockery as m;

class EcmtSubmitApplicationTest extends CommandHandlerTestCase
{
    public function setUp()
    {
        $this->mockRepo('EcmtPermitApplication', EcmtPermitApplication::class);
        $this->sut = new EcmtSubmitApplication();

        $this->mockedSmServices = [
            'PermitsCheckableCreateTaskCommandGenerator' => m::mock(CreateTaskCommandGenerator::class),
        ];

        parent::setUp();
    }

    protected function initReferences()
    {
        $this->refData = [
            EcmtPermitApplication::STATUS_UNDER_CONSIDERATION,
        ];

        parent::initReferences();
    }

    public function testHandleCommand()
    {
        $ecmtPermitApplicationId = 129;
        $licenceId = 705;
        $taskCreationMessage = 'Task created';

        $ecmtPermitApplication = m::mock(EcmtPermitApplication::class);
        $ecmtPermitApplication->shouldReceive('getId')
            ->andReturn($ecmtPermitApplicationId);
        $ecmtPermitApplication->shouldReceive('getLicence->getId')
            ->withNoArgs()
            ->andReturn($licenceId);
        $ecmtPermitApplication->shouldReceive('submit')
            ->with($this->mapRefData(EcmtPermitApplication::STATUS_UNDER_CONSIDERATION))
            ->once()
            ->globally()
            ->ordered();

        $this->repoMap['EcmtPermitApplication']->shouldReceive('fetchById')
            ->once()
            ->with($ecmtPermitApplicationId)
            ->andReturn($ecmtPermitApplication);

        $this->repoMap['EcmtPermitApplication']->shouldReceive('save')
            ->with($ecmtPermitApplication)
            ->once()
            ->globally()
            ->ordered();

        $expectedTaskParams = [
            'category' => Task::CATEGORY_PERMITS,
            'subCategory' => Task::SUBCATEGORY_APPLICATION,
            'description' => Task::TASK_DESCRIPTION_ANNUAL_ECMT_RECEIVED,
            'ecmtPermitApplication' => $ecmtPermitApplicationId,
            'licence' => $licenceId,
        ];

        $expectedTask = CreateTask::create($expectedTaskParams);

        $this->mockedSmServices['PermitsCheckableCreateTaskCommandGenerator']->shouldReceive('generate')
            ->with($ecmtPermitApplication)
            ->andReturn($expectedTask);

        $command = m::mock(CommandInterface::class);
        $command->shouldReceive('getId')
            ->once()
            ->withNoArgs()
            ->andReturn($ecmtPermitApplicationId);

        $this->expectedQueueSideEffect(
            $ecmtPermitApplicationId,
            Queue::TYPE_PERMITS_POST_SUBMIT,
            ['irhpPermitType' => IrhpPermitType::IRHP_PERMIT_TYPE_ID_ECMT]
        );

        $this->expectedSideEffect(
            CreateTask::class,
            $expectedTaskParams,
            (new Result())->addMessage($taskCreationMessage)
        );

        $result = $this->sut->handleCommand($command);

        $this->assertEquals(
            $ecmtPermitApplicationId,
            $result->getId('ecmtPermitApplication')
        );

        $this->assertEquals(
            [
                'Permit application updated',
                $taskCreationMessage
            ],
            $result->getMessages()
        );
    }
}
