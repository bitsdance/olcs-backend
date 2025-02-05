<?php

namespace Dvsa\Olcs\Api\Service\Cpms;

use Dvsa\Olcs\Cpms\Service\ApiServiceFactory as CpmsApiService;
use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;
use ZfcRbac\Service\AuthorizationService;
use Interop\Container\ContainerInterface;

class ApiServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return $this->__invoke($serviceLocator, CpmsApiService::class);
    }
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config = $container->get('Config');
        $authService = $container->get(AuthorizationService::class);
        $userId = $authService->getIdentity()->getUser()->getId();
        $apiService = new CpmsApiService($config, $userId);
        return $apiService->createApiService();
    }
}
