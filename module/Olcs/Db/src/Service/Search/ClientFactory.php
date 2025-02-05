<?php

namespace Olcs\Db\Service\Search;

use Elastica\Client;
use Olcs\Logging\Log\Logger;
use Olcs\Logging\Log\LaminasLogPsr3Adapter;
use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;
use Laminas\ServiceManager\Exception;
use Interop\Container\ContainerInterface;

/**
 * Class ClientFactory
 * @package Olcs\Db\Service\Search
 */
class ClientFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @throws \Laminas\ServiceManager\Exception\RuntimeException
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator): Client
    {
        return $this->__invoke($serviceLocator, Client::class);
    }

    /**
     * invoke method
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     * @param ContainerInterface $container
     * @param $requestedName
     * @param array|null $options
     * @return Client
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): Client
    {
        $config = $container->get('Config');
        if (!isset($config['elastic_search'])) {
            throw new Exception\RuntimeException('Elastic search config not found');
        }
        $service = new Client($config['elastic_search']);
        if (isset($config['elastic_search']['log'])) {
            $log = new LaminasLogPsr3Adapter(Logger::getLogger());
            $service->setLogger($log);
        }
        return $service;
    }
}
