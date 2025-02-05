<?php

namespace Dvsa\Olcs\Api\Service\Nr;

use Olcs\XmlTools\Xml\XmlNodeBuilder;
use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;
use Interop\Container\ContainerInterface;

/**
 * Class MsiResponseFactory
 * @package Dvsa\Olcs\Api\Service\Nr
 */
class MsiResponseFactory implements FactoryInterface
{
    const XML_NS_MSG = 'No config specified for xml ns';

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator service locator
     *
     * @return MsiResponse
     */
    public function createService(ServiceLocatorInterface $serviceLocator): MsiResponse
    {
        return $this->__invoke($serviceLocator, MsiResponse::class);
    }

    /**
     * invoke method
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     * @param ContainerInterface $container
     * @param $requestedName
     * @param array|null $options
     * @return MsiResponse
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): MsiResponse
    {
        $config = $container->get('Config');
        if (!isset($config['nr']['compliance_episode']['xmlNs'])) {
            throw new \RuntimeException(self::XML_NS_MSG);
        }
        $xmlBuilder = new XmlNodeBuilder('MS2ERRU_Infringement_Res', $config['nr']['compliance_episode']['xmlNs'], []);
        return new MsiResponse($xmlBuilder);
    }
}
