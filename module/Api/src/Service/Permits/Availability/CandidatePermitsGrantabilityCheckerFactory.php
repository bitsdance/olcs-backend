<?php

namespace Dvsa\Olcs\Api\Service\Permits\Availability;

use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;
use Interop\Container\ContainerInterface;

class CandidatePermitsGrantabilityCheckerFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return CandidatePermitsGrantabilityChecker
     */
    public function createService(ServiceLocatorInterface $serviceLocator): CandidatePermitsGrantabilityChecker
    {
        return $this->__invoke($serviceLocator, CandidatePermitsGrantabilityChecker::class);
    }

    /**
     * invoke method
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     * @param ContainerInterface $container
     * @param $requestedName
     * @param array|null $options
     * @return CandidatePermitsGrantabilityChecker
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): CandidatePermitsGrantabilityChecker
    {
        return new CandidatePermitsGrantabilityChecker(
            $container->get('PermitsAvailabilityCandidatePermitsAvailableCountCalculator')
        );
    }
}
