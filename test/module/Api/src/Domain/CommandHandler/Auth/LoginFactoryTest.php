<?php
declare(strict_types=1);

namespace Dvsa\OlcsTest\Api\Domain\CommandHandler\Auth;

use Dvsa\Olcs\Api\Domain\CommandHandler\Auth\Login;
use Dvsa\Olcs\Api\Domain\CommandHandler\Auth\LoginFactory;
use Dvsa\Olcs\Auth\Service\AuthenticationServiceInterface;
use Dvsa\OlcsTest\Api\Domain\CommandHandler\MocksAbstractCommandHandlerServicesTrait;
use Laminas\Authentication\Adapter\ValidatableAdapterInterface;
use Laminas\ServiceManager\ServiceManager;
use Mockery as m;
use Olcs\TestHelpers\MockeryTestCase;
use Olcs\TestHelpers\Service\MocksServicesTrait;

/**
 * Class LoginFactoryTest
 * @see LoginFactory
 */
class LoginFactoryTest extends MockeryTestCase
{
    use MocksServicesTrait;
    use MocksAbstractCommandHandlerServicesTrait;

    /**
     * @var LoginFactory
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
     */
    public function createService_IsCallable(): void
    {
        // Setup
        $this->setUpSut();

        // Assert
        $this->assertIsCallable([$this->sut, 'createService']);
    }

    /**
     * @test
     * @depends createService_IsCallable
     * @depends __invoke_IsCallable
     */
    public function createService_CallsInvoke(): void
    {
        // Setup
        $this->sut = m::mock(LoginFactory::class)->makePartial();

        // Expectations
        $this->sut->expects('__invoke')->withArgs(function ($serviceManager, $requestedName) {
            $this->assertSame($this->serviceManager(), $serviceManager, 'Expected first argument to be the ServiceManager passed to createService');
            $this->assertSame(null, $requestedName, 'Expected requestedName to be NULL');
            return true;
        });

        // Execute
        $this->sut->createService($this->serviceManager());
    }

    /**
     * @test
     * @depends __invoke_IsCallable
     */
    public function __invoke_ReturnsAnInstanceOfLoginCommandHandler(): void
    {
        // Setup
        $this->setUpSut();

        // Execute
        $result = $this->sut->__invoke($this->pluginManager(), null);

        // Assert
        $this->assertInstanceOf(Login::class, $result);
    }

    public function setUp(): void
    {
        $this->setUpServiceManager();
    }

    protected function setUpSut(): void
    {
        $this->sut = new LoginFactory();
    }

    /**
     * @param ServiceManager $serviceManager
     */
    protected function setUpDefaultServices(ServiceManager $serviceManager): void
    {
        $this->setUpAbstractCommandHandlerServices();
        $this->authenticationService();
        $this->adapter();
    }

    /**
     * @return AuthenticationServiceInterface|m\MockInterface
     */
    protected function authenticationService(): m\MockInterface
    {
        if (! $this->serviceManager->has(AuthenticationServiceInterface::class)) {
            $this->serviceManager->setService(
                AuthenticationServiceInterface::class,
                $this->setUpMockService(AuthenticationServiceInterface::class)
            );
        }

        return $this->serviceManager->get(AuthenticationServiceInterface::class);
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
}
