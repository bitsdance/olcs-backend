<?php

namespace Dvsa\Olcs\AcquiredRights\Client;

use GuzzleHttp\Client;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;
use Olcs\Logging\Log\Logger;

class AcquiredRightsClientFactory implements FactoryInterface
{
    protected const CONFIG_NAMESPACE = 'acquired_rights';
    protected const CONFIG_CLIENT_ROOT = 'client';
    protected const CONFIG_CLIENT_KEY_BASE_URL = 'base_uri';

    /**
     * @param ContainerInterface $container
     * @param $requestedName
     * @param array|null $options
     * @return AcquiredRightsClient
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): AcquiredRightsClient
    {
        if (method_exists($container, 'getServiceLocator') && $container->getServiceLocator()) {
            $container = $container->getServiceLocator();
        }

        $config = $container->get('Config');

        $httpClient = new Client($this->getAcquiredRightsClientConfiguration($config));
        return new AcquiredRightsClient(
            $httpClient
        );
    }

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return AcquiredRightsClient
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function createService(ServiceLocatorInterface $serviceLocator): AcquiredRightsClient
    {
        return $this->__invoke($serviceLocator, AcquiredRightsClient::class);
    }

    /**
     * Checks that the array path to base_url exists, and is not empty. Required for AcquiredRightsClient.
     *
     * @param array $config
     * @return array
     */
    protected function getAcquiredRightsClientConfiguration(array $config): array
    {
        $baseUrl = $config[static::CONFIG_NAMESPACE][static::CONFIG_CLIENT_ROOT][static::CONFIG_CLIENT_KEY_BASE_URL] ?? null;
        if (empty($baseUrl)) {
            $errorMsg = sprintf(
                'Expected configuration defined and not empty: %s -> %s -> %s',
                static::CONFIG_NAMESPACE,
                static::CONFIG_CLIENT_ROOT,
                static::CONFIG_CLIENT_KEY_BASE_URL
            );
            Logger::err($errorMsg);
            throw new \InvalidArgumentException($errorMsg);
        }

        // Minimum config requirements passed. Return Client configuration.
        return $config[static::CONFIG_NAMESPACE][static::CONFIG_CLIENT_ROOT];
    }
}
