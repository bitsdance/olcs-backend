<?php
declare(strict_types=1);

namespace Dvsa\Olcs\Api\Domain\CommandHandler\Tm;

use Dvsa\Olcs\Api\Domain\CommandHandler\TransactioningCommandHandler;
use Dvsa\Olcs\Auth\Adapter\OpenAm;
use Dvsa\Olcs\Auth\Service\PasswordService;
use Interop\Container\ContainerInterface;
use Laminas\Authentication\Adapter\ValidatableAdapterInterface;
use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorAwareInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;

class CreateNewUserFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param $requestedName
     * @param array|null $options
     * @return TransactioningCommandHandler
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): TransactioningCommandHandler
    {
        assert($container instanceof ServiceLocatorAwareInterface);
        $pluginManager = $container;
        $container = $container->getServiceLocator();

        $adapter = $container->get(ValidatableAdapterInterface::class);

        // TODO: Remove this once OpenAM has been removed
        if ($adapter instanceof OpenAm) {
            $adapter = null;
        }

        $passwordService = $container->get(PasswordService::class);
        return (new CreateNewUser($passwordService, $adapter))->createService($pluginManager);
    }

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return TransactioningCommandHandler
     * @deprecated Use __invoke
     */
    public function createService(ServiceLocatorInterface $serviceLocator): TransactioningCommandHandler
    {
        return $this->__invoke($serviceLocator, null);
    }
}
