<?php

namespace Dvsa\OlcsTest\Api\Domain\CommandHandler\Email;

use Dvsa\Olcs\Api\Domain\Command\Result;
use Dvsa\Olcs\Api\Domain\CommandHandler\CommandHandlerInterface;
use Dvsa\Olcs\Api\Domain\Repository\FeeType as FeeTypeRepo;
use Dvsa\Olcs\Api\Entity\ContactDetails\ContactDetails;
use Dvsa\Olcs\Api\Entity\Fee\Fee;
use Dvsa\Olcs\Api\Entity\Organisation\Organisation;
use Dvsa\Olcs\Api\Entity\User\User;
use Dvsa\Olcs\Email\Service\TemplateRenderer;
use Dvsa\Olcs\Transfer\Command\CommandInterface;
use Dvsa\OlcsTest\Api\Domain\CommandHandler\CommandHandlerTestCase;
use Mockery as m;

/**
 * Abstract permit email tester
 */
abstract class AbstractPermitTest extends CommandHandlerTestCase
{
    /** @var string */
    protected $commandClass = 'changeMe';

    /** @var string */
    protected $commandHandlerClass = 'changeMe';

    /** @var string */
    protected $template = 'changeMe';

    /** @var string */
    protected $subject = 'changeMe';

    /** @var string */
    protected $permitApplicationRepo = 'changeMe';

    /** @var string */
    protected $applicationEntityClass = 'changeMe';

    /** @var CommandHandlerInterface */
    protected $sut;

    /** @var mixed */
    protected $applicationEntity;

    /** @var CommandInterface */
    protected $commandEntity;

    /** @var string */
    protected $applicationRef;

    /** @var Organisation */
    protected $organisation;

    /** @var User */
    protected $userEntity;

    /** @var int */
    protected $permitAppId;

    public function setUp()
    {
        $this->permitAppId = 77;

        $this->applicationRef = 'OC1234567/1234';

        $this->commandEntity = $this->commandClass::create(['id' => $this->permitAppId]);

        $this->sut = new $this->commandHandlerClass();

        $this->mockRepo($this->permitApplicationRepo, '\\Dvsa\\Olcs\\Api\\Domain\\Repository\\' . $this->permitApplicationRepo);

        $this->mockRepo('FeeType', FeeTypeRepo::class);

        $this->mockedSmServices = [
            TemplateRenderer::class => m::mock(TemplateRenderer::class),
        ];

        $this->userEmail = 'email1@test.com';
        $this->orgEmails = [
            'orgEmail1@test.com',
            'orgEmail2@test.com'
        ];

        $this->contactDetails = m::mock(ContactDetails::class);

        $this->userEntity = m::mock(User::class);

        $this->organisation = m::mock(Organisation::class);

        $this->applicationEntity = m::mock($this->applicationEntityClass);
        $this->applicationEntity->shouldReceive('getApplicationRef')->withNoArgs()->andReturn($this->applicationRef);
        $this->applicationEntity->shouldReceive('getId')->withNoArgs()->andReturn($this->permitAppId);
        $this->applicationEntity->shouldReceive('getLicence->getOrganisation')
            ->once()
            ->withNoArgs()
            ->andReturn($this->organisation);

        $this->repoMap[$this->permitApplicationRepo]
            ->shouldReceive('fetchUsingId')
            ->with($this->commandEntity)
            ->once()
            ->andReturn($this->applicationEntity);

        $this->data = [
            'to' => $this->userEmail,
            'locale' => 'en_GB',
            'subject' => $this->subject,
        ];

        parent::setUp();
    }
}
