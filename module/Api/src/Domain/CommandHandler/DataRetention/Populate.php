<?php

namespace Dvsa\Olcs\Api\Domain\CommandHandler\DataRetention;

use Doctrine\DBAL\Connection;
use Dvsa\Olcs\Api\Domain\AuthAwareInterface;
use Dvsa\Olcs\Api\Domain\AuthAwareTrait;
use Dvsa\Olcs\Api\Domain\Command\Result;
use Dvsa\Olcs\Api\Domain\CommandHandler\AbstractCommandHandler;
use Dvsa\Olcs\Api\Domain\QueryHandlerManager;
use Dvsa\Olcs\Api\Domain\Repository\DataRetentionRule;
use Dvsa\Olcs\Transfer\Command\CommandInterface;
use Olcs\Logging\Log\Logger;
use Laminas\ServiceManager\ServiceLocatorInterface;
use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;

/**
 * Class Populate
 */
final class Populate extends AbstractCommandHandler implements AuthAwareInterface
{
    use AuthAwareTrait;

    /** @var Connection */
    private $connection;

    /**
     * @param ServiceLocatorInterface|QueryHandlerManager $serviceLocator
     *
     * @return AbstractCommandHandler|\Dvsa\Olcs\Api\Domain\CommandHandler\TransactioningCommandHandler
     */
    public function createService(ServiceLocatorInterface $serviceLocator, $name = null, $requestedName = null)
    {
        return $this->__invoke($serviceLocator, Populate::class);
    }

    protected $repoServiceName = 'DataRetentionRule';

    /**
     * Handle command
     *
     * @param CommandInterface $command DTO
     *
     * @return Result
     */
    public function handleCommand(CommandInterface $command)
    {
        /** @var DataRetentionRule $repo */
        $repo = $this->getRepo();

        /** @var \Dvsa\Olcs\Api\Entity\DataRetentionRule $dataRetentionRule */
        $enabledRules = $repo->fetchEnabledRules();

        foreach ($enabledRules['results'] as $dataRetentionRule) {
            $this->result->addMessage(
                sprintf(
                    'Running rule id %s, %s',
                    $dataRetentionRule->getId(),
                    $dataRetentionRule->getPopulateProcedure()
                )
            );
            try {
                $this->connection->beginTransaction();
                $result = $repo->runProc(
                    $dataRetentionRule->getPopulateProcedure(),
                    $this->getCurrentUser()->getId()
                );
                $this->connection->commit();
            } catch (\Exception $e) {
                $this->result->addMessage($e->getMessage());
                Logger::err(
                    sprintf(
                        'Error on rule id %s, %s: %s',
                        $dataRetentionRule->getId(),
                        $dataRetentionRule->getPopulateProcedure(),
                        $e->getMessage()
                    )
                );
                $this->connection->rollBack();
            }

            if (!$result) {
                $this->result->addMessage(
                    sprintf(
                        'Rule id %s, %s returned FALSE',
                        $dataRetentionRule->getId(),
                        $dataRetentionRule->getPopulateProcedure()
                    )
                );
            }
        }

        return $this->result;
    }
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $fullContainer = $container;
        
        if (method_exists($container, 'getServiceLocator') && $container->getServiceLocator()) {
            $container = $container->getServiceLocator();
        }
        /** @var EntityManager $entityManager */
        $entityManager = $container->get('DoctrineOrmEntityManager');
        $this->connection = $entityManager->getConnection();
        return parent::__invoke($fullContainer, $requestedName, $options);
    }
}
