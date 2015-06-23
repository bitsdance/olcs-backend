<?php

/**
 * Create ProposeToRevoke Test
 */
namespace Dvsa\OlcsTest\Api\Domain\CommandHandler\Cases\ProposeToRevoke;

use Mockery as m;
use Dvsa\Olcs\Api\Domain\CommandHandler\Cases\ProposeToRevoke\CreateProposeToRevoke;
use Dvsa\Olcs\Api\Domain\Repository\ProposeToRevoke;
use Dvsa\Olcs\Api\Entity\Cases\Cases;
use Dvsa\Olcs\Api\Entity\Pi\Reason;
use Dvsa\Olcs\Api\Entity\Pi\PresidingTc;
use Dvsa\Olcs\Api\Entity\Cases\ProposeToRevoke as ProposeToRevokeEntity;
use Dvsa\Olcs\Transfer\Command\Cases\ProposeToRevoke\CreateProposeToRevoke as Cmd;
use Dvsa\OlcsTest\Api\Domain\CommandHandler\CommandHandlerTestCase;

/**
 * Create ProposeToRevoke Test
 */
class CreateProposeToRevokeTest extends CommandHandlerTestCase
{
    public function setUp()
    {
        $this->sut = new CreateProposeToRevoke();
        $this->mockRepo('ProposeToRevoke', ProposeToRevoke::class);

        parent::setUp();
    }

    protected function initReferences()
    {
        $this->references = [
            Cases::class => [
                11 => m::mock(Cases::class)
            ],
            Reason::class => [
                221 => m::mock(Reason::class)
            ],
            PresidingTc::class => [
                1 => m::mock(PresidingTc::class)
            ],
        ];

        parent::initReferences();
    }

    public function testHandleCommand()
    {
        $data = [
            'case' => 11,
            'reasons' => [221],
            'presidingTc' => 1,
            'ptrAgreedDate' => '2015-01-01',
            'closedDate' => '2016-01-01',
            'comment' => 'testing',
        ];

        $command = Cmd::create($data);

        /** @var ProposeToRevokeEntity $savedProposeToRevoke */
        $savedProposeToRevoke = null;

        $this->repoMap['ProposeToRevoke']->shouldReceive('save')
            ->once()
            ->with(m::type(ProposeToRevokeEntity::class))
            ->andReturnUsing(
                function (ProposeToRevokeEntity $proposeToRevoke) use (&$savedProposeToRevoke) {
                    $proposeToRevoke->setId(111);
                    $savedProposeToRevoke = $proposeToRevoke;
                }
            );

        $result = $this->sut->handleCommand($command);

        $expected = [
            'id' => [
                'proposeToRevoke' => 111,
            ],
            'messages' => [
                'Revocation created successfully'
            ]
        ];

        $this->assertEquals($expected, $result->toArray());

        $this->assertSame(
            $this->references[Cases::class][$data['case']],
            $savedProposeToRevoke->getCase()
        );
        $this->assertSame(
            $this->references[Reason::class][$data['reasons'][0]],
            $savedProposeToRevoke->getReasons()[0]
        );
        $this->assertSame(
            $this->references[PresidingTc::class][$data['presidingTc']],
            $savedProposeToRevoke->getPresidingTc()
        );
        $this->assertEquals($data['ptrAgreedDate'], $savedProposeToRevoke->getPtrAgreedDate()->format('Y-m-d'));
        $this->assertEquals($data['closedDate'], $savedProposeToRevoke->getClosedDate()->format('Y-m-d'));
        $this->assertEquals($data['comment'], $savedProposeToRevoke->getComment());
    }
}
