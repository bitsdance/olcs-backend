<?php

/**
 * Clean up abandoned variations
 */
namespace Dvsa\Olcs\Cli\Domain\CommandHandler;

use Dvsa\Olcs\Api\Domain\Command\Result;
use Dvsa\Olcs\Api\Domain\CommandHandler\AbstractCommandHandler;
use Dvsa\Olcs\Api\Domain\CommandHandler\TransactioningCommandHandler;
use Dvsa\Olcs\Transfer\Command\CommandInterface;
use Interop\Container\Containerinterface;
use Laminas\ServiceManager\ServiceLocatorInterface;
use Dvsa\Olcs\Api\Domain\Repository\Application;
use Dvsa\Olcs\Api\Entity\Application\Application as ApplicationEntity;
use Dvsa\Olcs\Transfer\Command\Variation\DeleteVariation;

/**
 * Remove abandoned variations
 */

final class CleanUpAbandonedVariations extends AbstractCommandHandler
{
    protected $repoServiceName = 'Application';

    protected $olderThan = '4 hours';

    /**
     * create service
     *
     * @param ServiceLocatorInterface $serviceLocator service locator
     *
     * @return $this|TransactioningCommandHandler
     */
    public function createService(ServiceLocatorInterface $serviceLocator, $name = null, $requestedName = null)
    {
        return $this->__invoke($serviceLocator, CleanUpAbandonedVariations::class);
    }

    /**
     * handle command
     *
     * @param CommandInterface $command command
     *
     * @return Result
     */
    public function handleCommand(CommandInterface $command)
    {
        $olderThanDate = date('Y-m-d H:i:s', strtotime('-' . $this->olderThan));

        /** @var Application $repo */
        $repo = $this->getRepo();

        $abandonedVariations = $repo->fetchAbandonedVariations($olderThanDate);

        /* @var $variation ApplicationEntity */
        foreach ($abandonedVariations as $variation) {
            $this->handleSideEffect(DeleteVariation::create(['id' => $variation->getId()]));
            $this->result->addId('variation ' . $variation->getId(), $variation->getId());
        }

        $this->result->addMessage(count($abandonedVariations) . ' abandoned variation records deleted');

        return $this->result;
    }
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $fullContainer = $container;
        
        if (method_exists($container, 'getServiceLocator') && $container->getServiceLocator()) {
            $container = $container->getServiceLocator();
        }

        $config = $container->get('Config');
        if (isset($config['batch_config']['clean-abandoned-variations']['older-than'])) {
            $this->olderThan = $config['batch_config']['clean-abandoned-variations']['older-than'];
        }
        return parent::__invoke($fullContainer, $requestedName, $options);
    }
}
