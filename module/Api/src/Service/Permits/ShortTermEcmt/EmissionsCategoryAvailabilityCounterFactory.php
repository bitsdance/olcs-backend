<?php

namespace Dvsa\Olcs\Api\Service\Permits\ShortTermEcmt;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class EmissionsCategoryAvailabilityCounterFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return EmissionsCategoryAvailabilityCounter
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $repoServiceManager = $serviceLocator->get('RepositoryServiceManager');

        return new EmissionsCategoryAvailabilityCounter(
            $repoServiceManager->get('IrhpPermitRange'),
            $repoServiceManager->get('IrhpPermitApplication'),
            $repoServiceManager->get('IrhpPermit')
        );
    }
}
