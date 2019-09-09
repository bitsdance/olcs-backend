<?php

namespace Dvsa\Olcs\Api\Service\Permits\Scoring;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class SuccessfulCandidatePermitsWriterFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return SuccessfulCandidatePermitsWriter
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new SuccessfulCandidatePermitsWriter(
            $serviceLocator->get('RepositoryServiceManager')->get('IrhpCandidatePermit')
        );
    }
}
