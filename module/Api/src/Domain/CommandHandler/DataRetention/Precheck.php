<?php

namespace Dvsa\Olcs\Api\Domain\CommandHandler\DataRetention;

use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManager;
use Dvsa\Olcs\Api\Domain\Command\DataRetention\Precheck as PrecheckCommand;
use Dvsa\Olcs\Api\Domain\Command\Result;
use Dvsa\Olcs\Api\Domain\CommandHandler\AbstractCommandHandler;
use Dvsa\Olcs\Api\Domain\QueryHandlerManager;
use Dvsa\Olcs\Api\Domain\Repository\SystemParameter;
use Dvsa\Olcs\Transfer\Command\CommandInterface;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;

/**
 * DR Precheck
 */
final class Precheck extends AbstractCommandHandler
{
    /** @var Connection */
    private $connection;

    protected $extraRepos = ['SystemParameter'];

    /**
     * @param ServiceLocatorInterface|QueryHandlerManager $serviceLocator
     *
     * @return AbstractCommandHandler|\Dvsa\Olcs\Api\Domain\CommandHandler\TransactioningCommandHandler
     */
    public function createService(ServiceLocatorInterface $serviceLocator, $name = null, $requestedName = null)
    {
        return $this->__invoke($serviceLocator, Precheck::class);
    }

    /**
     * Handle command
     *
     * @param CommandInterface|PrecheckCommand $command DTO
     *
     * @return Result
     */
    public function handleCommand(CommandInterface $command)
    {
        $limit = $command->getLimit();
        if ($limit <= 0) {
            $limit = $this->getDefaultLimit();
            $this->result->addMessage(
                "Limit option not set or invalid; defaulting to SystemParameter::DR_DELETE_LIMIT=$limit"
            );
        }

        $this->result->addMessage(
            "Calling stored procedure sp_dr_precheck($limit)"
        );
        $stmt = $this->connection->prepare("CALL sp_dr_precheck($limit);");
        $stmt->execute();
        $this->result->addMessage("Precheck procedure executed.");
        return $this->result;
    }

    /**
     * Fetches the default Data Retention Delete Limit from the SystemParameter table.
     *
     * @return int
     * @throws \Dvsa\Olcs\Api\Domain\Exception\RuntimeException
     */
    private function getDefaultLimit(): int
    {
        /** @var SystemParameter $systemParameterRepo */
        $systemParameterRepo = $this->getRepo('SystemParameter');

        return $systemParameterRepo->getDataRetentionDeleteLimit();
    }
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $fullContainer = $container;
        
        if (method_exists($container, 'getServiceLocator') && $container->getServiceLocator()) {
            $container = $container->getServiceLocator();
        }
        /** @var EntityManager $entityManager */
        $entityManager = $container->get('DoctrineOrmEntityManager');
        $this->connection = $entityManager->getConnection()->getWrappedConnection();
        return parent::__invoke($fullContainer, $requestedName, $options);
    }
}
