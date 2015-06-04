<?php

/**
 * Abstract Command Handler
 *
 * @author Rob Caiger <rob@clocal.co.uk>
 */
namespace Dvsa\Olcs\Api\Domain\CommandHandler;

use Dvsa\Olcs\Api\Domain\Exception\RuntimeException;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Dvsa\Olcs\Api\Domain\Repository\RepositoryInterface;
use ZfcRbac\Service\AuthorizationService;
use Dvsa\Olcs\Api\Domain\AuthAwareInterface;

/**
 * Abstract Command Handler
 *
 * @author Rob Caiger <rob@clocal.co.uk>
 */
abstract class AbstractCommandHandler implements CommandHandlerInterface, FactoryInterface
{
    /**
     * @var RepositoryInterface
     */
    private $repo;

    /**
     * @var CommandHandlerInterface
     */
    private $commandHandler;

    protected $repoServiceName;

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var ServiceLocatorInterface $mainServiceLocator  */
        $mainServiceLocator = $serviceLocator->getServiceLocator();

        if ($this instanceof AuthAwareInterface) {
            $this->setAuthService($mainServiceLocator->get(AuthorizationService::class));
        }

        if ($this->repoServiceName === null) {
            throw new RuntimeException('The repoServiceName property must be define in a CommandHandler');
        }

        $this->repo = $mainServiceLocator->get('RepositoryServiceManager')
            ->get($this->repoServiceName);

        $this->commandHandler = $serviceLocator;

        if ($this instanceof TransactionedInterface) {
            $repo = $mainServiceLocator->get('RepositoryServiceManager')->get('Repository');
            return new TransactioningCommandHandler($this, $repo);
        }

        return $this;
    }

    /**
     * @return RepositoryInterface
     */
    protected function getRepo()
    {
        return $this->repo;
    }

    /**
     * @return CommandHandlerInterface
     */
    protected function getCommandHandler()
    {
        return $this->commandHandler;
    }
}
