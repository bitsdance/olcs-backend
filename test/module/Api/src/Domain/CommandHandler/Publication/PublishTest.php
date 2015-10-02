<?php

/**
 * PublishTest.php
 */
namespace Dvsa\OlcsTest\Api\Domain\CommandHandler\Publication;

use Mockery as m;
use Dvsa\OlcsTest\Api\Domain\CommandHandler\CommandHandlerTestCase;
use Dvsa\Olcs\Api\Domain\Repository\Publication as PublicationRepo;
use Dvsa\Olcs\Api\Domain\CommandHandler\Publication\Publish;
use Dvsa\Olcs\Api\Entity\Publication\Publication as PublicationEntity;
use Dvsa\Olcs\Transfer\Command\Publication\Publish as PublishCommand;
use Dvsa\Olcs\Api\Entity\System\RefData;

/**
 * Class PublishTest
 */
class PublishTest extends CommandHandlerTestCase
{
    public function setUp()
    {
        $this->sut = new Publish();
        $this->mockRepo('Publication', PublicationRepo::class);

        parent::setUp();
    }

    protected function initReferences()
    {
        $this->refData = [PublicationEntity::PUB_PRINTED_STATUS];

        parent::initReferences();
    }

    public function testHandleCommand()
    {
        $id = 11;
        $data = ['id' => $id];

        $command = PublishCommand::create($data);

        $publicationEntity = m::mock(PublicationEntity::class)->makePartial();
        $publicationEntity->setId($id);
        $publicationEntity->setPubStatus(new RefData(PublicationEntity::PUB_GENERATED_STATUS));
        $publicationEntity->shouldReceive('publish')
            ->once()
            ->with($this->refData[PublicationEntity::PUB_PRINTED_STATUS])
            ->andReturnSelf();

        $this->repoMap['Publication']
            ->shouldReceive('fetchUsingId')
            ->once()
            ->with($command)
            ->andReturn($publicationEntity);

        $this->repoMap['Publication']->shouldReceive('save')
            ->once()
            ->with(m::type(PublicationEntity::class))
            ->andReturnUsing(
                function (PublicationEntity $publicationEntity) use (&$savedPublication) {
                    $publicationEntity->setId(11);
                    $savedPublication = $publicationEntity;
                }
            );

        $result = $this->sut->handleCommand($command);

        $expected = [
            'id' => [
                'Publication' => $id
            ],
            'messages' => [
                'Publication was published'
            ]
        ];

        $this->assertEquals($expected, $result->toArray());
    }
}
