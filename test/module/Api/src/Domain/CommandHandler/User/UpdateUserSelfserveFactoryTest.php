<?php

declare(strict_types=1);

namespace Dvsa\OlcsTest\Api\Domain\CommandHandler\User;

use Dvsa\Olcs\Api\Domain\CommandHandler\TransactioningCommandHandler;
use Dvsa\Olcs\Api\Domain\CommandHandler\User\UpdateUserSelfserve;
use Dvsa\Olcs\Api\Domain\CommandHandler\User\UpdateUserSelfserveFactory;
use Dvsa\Olcs\Api\Domain\RepositoryServiceManager;
use Dvsa\Olcs\Api\Domain\Repository\User;
use Dvsa\Olcs\Api\Service\EventHistory\Creator as EventHistoryCreator;
use Dvsa\Olcs\Api\Service\OpenAm\UserInterface;
use Dvsa\Olcs\Auth\Service\PasswordService;
use Dvsa\OlcsTest\Api\Domain\CommandHandler\MocksAbstractCommandHandlerServicesTrait;
use Dvsa\OlcsTest\MocksRepositoriesTrait;
use Laminas\Authentication\Adapter\ValidatableAdapterInterface;
use Laminas\ServiceManager\ServiceManager;
use Mockery as m;
use Olcs\TestHelpers\MockeryTestCase;
use Olcs\TestHelpers\Service\MocksServicesTrait;
use ZfcRbac\Service\AuthorizationService;

/**
 * @covers \Dvsa\Olcs\Api\Domain\CommandHandler\User\UpdateUserSelfserveFactory
 */
class UpdateUserSelfserveFactoryTest extends MockeryTestCase
{
    use MocksServicesTrait;
    use MocksRepositoriesTrait;
    use MocksAbstractCommandHandlerServicesTrait;

    /**
     * @var UpdateUserSelfserveFactory
     */
    protected $sut;

    /**
     * @test
     */
    public function __invoke_IsCallable(): void
    {
        // Setup
        $this->setUpSut();

        // Assert
        $this->assertIsCallable([$this->sut, '__invoke']);
    }

    /**
     * @test
     * @deprecated
     */
    public function createService_IsCallable()
    {
        // Setup
        $this->setUpSut();

        // Assert
        $this->assertIsCallable([$this->sut, 'createService']);
    }

    /**
     * @test
     * @depends    createService_IsCallable
     * @depends    __invoke_IsCallable
     * @deprecated
     */
    public function createService_CallsInvoke()
    {
        // Setup
        $this->sut = m::mock(UpdateUserSelfserveFactory::class)->makePartial();

        // Expectations
        $this->sut->expects('__invoke')->withArgs(
            function ($serviceManager, $requestedName) {
                $this->assertSame($this->serviceManager(), $serviceManager, 'Expected first argument to be the ServiceManager passed to createService');
                $this->assertSame(UpdateUserSelfserve::class, $requestedName, 'Expected requestedName to be '. UpdateUserSelfserve::class);
                return true;
            }
        );

        // Execute
        $this->sut->createService($this->serviceManager());
    }

    /**
     * @test
     * @depends __invoke_IsCallable
     */
    public function __invoke_ReturnsAnInstanceOfTransactioningCommandHandler()
    {
        // Setup
        $this->setUpSut();

        // Expectations
        $repositoryServiceManager = $this->repositoryServiceManager();
        $repositoryServiceManager->expects('get')->with('User')->andReturn(m::mock(User::class));

        // Execute
        $result = $this->sut->__invoke($this->pluginManager(), null);

        // Assert
        $this->assertInstanceOf(TransactioningCommandHandler::class, $result);
    }

    public function setUp(): void
    {
        $this->setUpServiceManager();
    }

    protected function setUpSut(): void
    {
        $this->sut = new UpdateUserSelfserveFactory();
    }

    /**
     * @param ServiceManager $serviceManager
     */
    protected function setUpDefaultServices(ServiceManager $serviceManager): void
    {
        $this->setUpAbstractCommandHandlerServices();
        $this->authorisationService();
        $this->eventHistoryCreator();
        $this->passwordService();
        $this->adapter();
        $this->userInterface();
        $this->config();
        $this->repositoryServiceManager();
    }

    /**
     * @return AuthorizationService|m\MockInterface
     */
    protected function authorisationService(): m\MockInterface
    {
        if (! $this->serviceManager->has(AuthorizationService::class)) {
            $this->serviceManager->setService(
                AuthorizationService::class,
                $this->setUpMockService(AuthorizationService::class)
            );
        }

        return $this->serviceManager->get(AuthorizationService::class);
    }

    /**
     * @return EventHistoryCreator|m\MockInterface
     */
    protected function eventHistoryCreator(): m\MockInterface
    {
        if (! $this->serviceManager->has(EventHistoryCreator::class)) {
            $this->serviceManager->setService(
                EventHistoryCreator::class,
                $this->setUpMockService(EventHistoryCreator::class)
            );
        }

        return $this->serviceManager->get(EventHistoryCreator::class);
    }

    /**
     * @return PasswordService|m\MockInterface
     */
    protected function passwordService(): m\MockInterface
    {
        if (! $this->serviceManager->has(PasswordService::class)) {
            $this->serviceManager->setService(
                PasswordService::class,
                $this->setUpMockService(PasswordService::class)
            );
        }

        return $this->serviceManager->get(PasswordService::class);
    }

    /**
     * @return ValidatableAdapterInterface|m\MockInterface
     */
    protected function adapter(): m\MockInterface
    {
        if (! $this->serviceManager->has(ValidatableAdapterInterface::class)) {
            $this->serviceManager->setService(
                ValidatableAdapterInterface::class,
                $this->setUpMockService(ValidatableAdapterInterface::class)
            );
        }

        return $this->serviceManager->get(ValidatableAdapterInterface::class);
    }

    /**
     * @return array
     */
    protected function config(): array
    {
        if (! $this->serviceManager->has('Config')) {
            $this->serviceManager->setService(
                'Config',
                []
            );
        }

        return $this->serviceManager->get('Config');
    }

    /**
     * @return UserInterface|m\MockInterface
     */
    protected function userInterface(): m\MockInterface
    {
        if (! $this->serviceManager->has(UserInterface::class)) {
            $this->serviceManager->setService(
                UserInterface::class,
                $this->setUpMockService(UserInterface::class)
            );
        }

        return $this->serviceManager->get(UserInterface::class);
    }

    /**
     * @return RepositoryServiceManager|m\MockInterface
     */
    private function repositoryServiceManager(): m\MockInterface
    {
        if (!$this->serviceManager->has('RepositoryServiceManager')) {
            $instance = $this->setUpMockService(RepositoryServiceManager::class);
            $this->serviceManager->setService('RepositoryServiceManager', $instance);
        }

        return $this->serviceManager->get('RepositoryServiceManager');
    }
}
