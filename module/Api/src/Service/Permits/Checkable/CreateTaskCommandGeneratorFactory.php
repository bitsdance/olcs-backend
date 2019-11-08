<?php

namespace Dvsa\Olcs\Api\Service\Permits\Checkable;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class CreateTaskCommandGeneratorFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return CreateTaskCommandGenerator
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new CreateTaskCommandGenerator(
            $serviceLocator->get('PermitsCheckableCreateTaskCommandFactory')
        );
    }
}
